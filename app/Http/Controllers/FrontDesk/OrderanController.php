<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\Staff;
use App\Models\Orderan;
use App\Models\Asuransi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderanController extends Controller
{
    public function index()
    {
        $cabangId = session('cabang_id');

        $orderans = Orderan::where('cabang_id', $cabangId)
            ->with('user') // jika ingin ambil data customer
            ->orderByDesc('created_at')
            ->get();

        return view('Informasi.orderan', compact('orderans'));
    }

    public function orderanDetail($id)
    {
        $optometristList = Staff::where("cabang_id", session('cabang_id'))->get();
        $asuransiList = Asuransi::where("cabang_id", session('cabang_id'))->get();
        $order = Orderan::with([
            'items.itemable',
            'resep'
        ])->findOrFail($id);

        return view('Informasi.detailOrderan', compact('order', 'optometristList', 'asuransiList'));
    }

    public function updateOrderan(Request $request, $id)
    {
        $order = Orderan::find($id);
        $isUserAdmin = (Auth::user()->role ?? '') == 'admin';
        $originalOrderStatus = $order->order_status;
        $requestedOrderStatus = $request->input('order_status');

        if ($originalOrderStatus == 'complete' && !$isUserAdmin) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah order yang sudah selesai.');
        }

        $customerPayingCleaned = (float) str_replace(['.', ','], '', $request->input('customer_paying'));
        $request->merge(['customer_paying_cleaned' => $customerPayingCleaned]);

        $validatedData = $request->validate([
            'resep_left_sph_d' => 'nullable|string|max:255',
            'resep_left_cyl_d' => 'nullable|string|max:255',
            'resep_left_axis_d' => 'nullable|string|max:255',
            'resep_left_va_d' => 'nullable|string|max:255',
            'resep_right_sph_d' => 'nullable|string|max:255',
            'resep_right_cyl_d' => 'nullable|string|max:255',
            'resep_right_axis_d' => 'nullable|string|max:255',
            'resep_right_va_d' => 'nullable|string|max:255',
            'resep_add_left' => 'nullable|string|max:255',
            'resep_add_right' => 'nullable|string|max:255',
            'resep_pd_left' => 'nullable|string|max:255',
            'resep_pd_right' => 'nullable|string|max:255',
            'resep_notes' => 'nullable|string',
            'order_date' => 'required|date',
            'complete_date' => 'nullable|date',
            'staff_id' => 'required|exists:staff,id',
            'payment_type' => ['required', Rule::in(['DP', 'pelunasan', 'asuransi'])],
            'asuransi_id' => [
                'nullable',
                Rule::requiredIf($request->input('payment_type') == 'asuransi'),
                'exists:asuransis,id',
            ],
            'order_status' => ['required', Rule::in(['pending', 'complete'])],
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'payment_status' => ['required', Rule::in(['unpaid', 'paid'])],
            'customer_paying_cleaned' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $calculatedTotal = $order->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $asuransiNominal = 0;
            if ($validatedData['payment_type'] == 'asuransi' && isset($validatedData['asuransi_id'])) {
                $asuransi = Asuransi::find($validatedData['asuransi_id']);
                if ($asuransi) {
                    $asuransiNominal = $asuransi->nominal;
                }
            }

            $perluDibayar = $calculatedTotal - $asuransiNominal;
            $kembalian = $validatedData['customer_paying_cleaned'] - $perluDibayar;

            if ($requestedOrderStatus == 'complete' && $originalOrderStatus != 'complete') {
                foreach ($order->items as $item) {
                    $product = $item->itemable;

                    if (!$product) {
                        throw new \Exception("Produk terkait dengan item order (ID: {$item->id}) tidak ditemukan.");
                    }



                    if ($product->stok < $item->quantity) {
                        throw new \Exception("Stok {$product->merk} ({$item->quantity} diminta, {$product->stok} tersedia) tidak mencukupi untuk item order ID {$item->id}.");
                    }

                    $product->stok -= $item->quantity;
                    $product->save();
                }
                $order->complete_date = now();
                if ($order->payment_status == 'unpaid') {
                    $order->payment_status = 'paid';
                }
            }

            $order->update([
                'order_date' => $validatedData['order_date'],
                'complete_date' => $order->complete_date,
                'staff_id' => $validatedData['staff_id'],
                'payment_type' => $validatedData['payment_type'],
                'asuransi_id' => $validatedData['asuransi_id'],
                'order_status' => $validatedData['order_status'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_status'],
                'customer_paying' => $validatedData['customer_paying_cleaned'],
                'total' => $calculatedTotal,
                'perlu_dibayar' => $perluDibayar,
                'kembalian' => $kembalian,
            ]);

            if ($order->resep) {
                $order->resep->update([
                    'right_sph_d' => $validatedData['resep_right_sph_d'],
                    'right_cyl_d' => $validatedData['resep_right_cyl_d'],
                    'right_axis_d' => $validatedData['resep_right_axis_d'],
                    'right_va_d' => $validatedData['resep_right_va_d'],
                    'left_sph_d' => $validatedData['resep_left_sph_d'],
                    'left_cyl_d' => $validatedData['resep_left_cyl_d'],
                    'left_axis_d' => $validatedData['resep_left_axis_d'],
                    'left_va_d' => $validatedData['resep_left_va_d'],
                    'add_right' => $validatedData['resep_add_right'],
                    'add_left' => $validatedData['resep_add_left'],
                    'pd_right' => $validatedData['resep_pd_right'],
                    'pd_left' => $validatedData['resep_pd_left'],
                    'notes' => $validatedData['resep_notes'],
                ]);
            } else {
                $order->resep()->create([
                    'right_sph_d' => $validatedData['resep_right_sph_d'],
                    'right_cyl_d' => $validatedData['resep_right_cyl_d'],
                    'right_axis_d' => $validatedData['resep_right_axis_d'],
                    'right_va_d' => $validatedData['resep_right_va_d'],
                    'left_sph_d' => $validatedData['resep_left_sph_d'],
                    'left_cyl_d' => $validatedData['resep_left_cyl_d'],
                    'left_axis_d' => $validatedData['resep_left_axis_d'],
                    'left_va_d' => $validatedData['resep_left_va_d'],
                    'add_right' => $validatedData['resep_add_right'],
                    'add_left' => $validatedData['resep_add_left'],
                    'pd_right' => $validatedData['resep_pd_right'],
                    'pd_left' => $validatedData['resep_pd_left'],
                    'notes' => $validatedData['resep_notes'],
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }
}
