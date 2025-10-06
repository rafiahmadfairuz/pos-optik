<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\Staff;
use App\Models\Cabang;
use App\Models\Orderan;
use App\Models\Asuransi;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

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
            'resep',
            'user'
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
        $diskonCleaned = (float) str_replace(['.', ','], '', $request->input('diskon')); // ✅ Tambahan
        $request->merge([
            'customer_paying_cleaned' => $customerPayingCleaned,
            'diskon_cleaned' => $diskonCleaned, // ✅ Tambahan
        ]);

        $validatedData = $request->validate([
            'resep_right_sph_d' => 'nullable|numeric|between:-20,20|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_right_cyl_d' => 'nullable|numeric|between:-6,6|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_right_axis_d' => 'nullable|numeric|regex:/^\d{1,3}$/|between:0,180',
            'resep_right_va_d' => 'nullable|numeric',
            'resep_left_sph_d' => 'nullable|numeric|between:-20,20|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_left_cyl_d' => 'nullable|numeric|between:-6,6|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_left_axis_d' => 'nullable|numeric|regex:/^\d{1,3}$/|between:0,180',
            'resep_left_va_d' => 'nullable|numeric',
            'resep_add_right' => 'nullable|numeric|between:0.75,3.5|regex:/^\d+(\.\d{1,2})?$/',
            'resep_add_left' => 'nullable|numeric|between:0.75,3.5|regex:/^\d+(\.\d{1,2})?$/',
            'resep_pd_right' => 'nullable|numeric|between:25,40|regex:/^\d+(\.\d{1,2})?$/',
            'resep_pd_left' => 'nullable|numeric|between:25,40|regex:/^\d+(\.\d{1,2})?$/',
            'tanggal_pemeriksaan' => 'nullable',
            'resep_notes' => 'nullable|string',
            'order_date' => 'required|date',
            'complete_date' => 'required|date',
            'staff_id' => 'required|exists:staff,id',
            'payment_type' => ['required', Rule::in(['pelunasan', 'asuransi'])],
            'asuransi_id' => [
                'nullable',
                Rule::requiredIf($request->input('payment_type') == 'asuransi'),
                'exists:asuransis,id',
            ],
            'order_status' => ['required', Rule::in(['pending', 'complete'])],
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'payment_status' => ['required', Rule::in(['DP', 'unpaid', 'paid'])],
            'customer_paying_cleaned' => 'required|numeric|min:0',
            'diskon_cleaned' => 'nullable|numeric|min:0', // ✅ Tambahan

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

            $diskon = $validatedData['diskon_cleaned'] ?? 0; // ✅ Tambahan
            $perluDibayar = $calculatedTotal - $asuransiNominal - $diskon; // ✅ Update formula

            // ✅ Tambahan akumulasi pembayaran
            $customerPayingAkhir = $order->customer_paying + $validatedData['customer_paying_cleaned'];
            $kurangBayar = max($perluDibayar - $customerPayingAkhir, 0); // ✅ Perbaikan
            $kembalian = max($customerPayingAkhir - $perluDibayar, 0); // ✅ Perbaikan

            if ($requestedOrderStatus == 'complete' && $originalOrderStatus != 'complete') {
                $currentCabangId = session('cabang_id');

                foreach ($order->items as $item) {
                    $itemableType = $item->itemable_type; // alias morphMap, contoh: 'frame', 'softlens'
                    $itemableId   = $item->itemable_id;

                    // 🔍 ambil model class dari morphMap
                    $morphMap = Relation::morphMap();
                    $modelClass = $morphMap[$itemableType] ?? null;

                    if (!$modelClass) {
                        throw new \Exception("Tipe produk {$itemableType} tidak dikenali untuk item ID {$item->id}.");
                    }

                    // 🔍 ambil stok produk cabang yang sesuai
                    $produkCabang = ProdukCabang::where('cabang_id', $currentCabangId)
                        ->where('itemable_id', $itemableId)
                        ->where('itemable_type', $itemableType)
                        ->first();

                    if (!$produkCabang) {
                        throw new \Exception("Produk cabang tidak ditemukan untuk item #{$item->id} ({$itemableType}).");
                    }

                    if ($produkCabang->stok < $item->quantity) {
                        $namaProduk = $produkCabang->itemable->merk ?? $produkCabang->itemable->nama ?? 'Produk';
                        throw new \Exception("Stok {$namaProduk} tidak mencukupi. Diminta {$item->quantity}, tersedia {$produkCabang->stok}.");
                    }

                    // 🔻 Kurangi stok cabang
                    $produkCabang->decrement('stok', $item->quantity);

                    Log::info("[ORDER COMPLETE] Stok cabang dikurangi", [
                        'cabang_id'   => $currentCabangId,
                        'itemable_id' => $itemableId,
                        'itemable_type' => $itemableType,
                        'qty'         => $item->quantity,
                        'stok_sisa'   => $produkCabang->stok,
                    ]);
                }

                // 🧾 Set status order
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
                'customer_paying' => $customerPayingAkhir, // ✅ Akumulasi
                'diskon' => $diskon, // ✅ Tambahan
                'kurang_bayar' => $kurangBayar, // ✅ Tambahan
                'total' => $calculatedTotal,
                'perlu_dibayar' => $perluDibayar,
                'kembalian' => $kembalian,
            ]);

            $resepData = [
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
                'tanggal_pemeriksaan' => $validatedData['tanggal_pemeriksaan'],
                'notes' => $validatedData['resep_notes'],

            ];

            if ($order->resep) {
                $order->resep->update($resepData);
            } else {
                $order->resep()->create($resepData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }


    public function cetakNota($id)
    {
        $order = Orderan::with('asuransi')->findOrFail($id);
        $cabang = Cabang::findOrFail(session('cabang_id'));

        $pdf = Pdf::loadView('nota', compact('order', 'cabang'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('nota-' . $order->id . '.pdf');
    }
    public function returOrderan($id)
    {
        $order = Orderan::with(['items.itemable'])->findOrFail($id);

        $currentCabangId = session('cabang_id');
        if (!$currentCabangId) {
            return back()->with('error', 'Cabang aktif tidak ditemukan di sesi.');
        }

        if ((int) $order->cabang_id !== (int) $currentCabangId) {
            return back()->with('error', 'Order ini bukan milik cabang yang sedang aktif.');
        }

        if ($order->order_status !== 'complete') {
            return back()->with('warning', 'Hanya order status complete yang bisa diretur.');
        }

        if (Schema::hasColumn('orderans', 'is_returned') && $order->is_returned) {
            return back()->with('warning', 'Order ini sudah diretur sebelumnya.');
        }

        try {
            DB::transaction(function () use ($order, $currentCabangId) {

                foreach ($order->items as $item) {
                    $itemableType = $item->itemable_type; // contoh: 'frame', 'softlens', dll (alias morphMap)
                    $itemableId   = $item->itemable_id;

                    // 🔍 Cek relasi morphMap untuk dapatkan model class
                    $morphMap = Relation::morphMap();
                    $modelClass = $morphMap[$itemableType] ?? null;

                    if (!$modelClass) {
                        throw new \Exception("Tipe produk {$itemableType} tidak dikenali.");
                    }

                    // 🔍 Ambil ProdukCabang sesuai tipe dan cabang aktif
                    $produkCabang = ProdukCabang::where('cabang_id', $currentCabangId)
                        ->where('itemable_id', $itemableId)
                        ->where('itemable_type', $itemableType)
                        ->first();

                    if (!$produkCabang) {
                        throw new \Exception("Produk cabang tidak ditemukan untuk item #{$item->id} ({$itemableType}).");
                    }

                    // 🔁 Tambahkan stok kembali ke cabang
                    $qty = (int) $item->quantity;
                    $stokSebelum = (int) $produkCabang->stok;
                    $produkCabang->increment('stok', $qty);

                    Log::info("=== RETUR ORDER #{$order->id} ===");
                    Log::info("Tipe Produk: {$itemableType}");
                    Log::info("Cabang: {$currentCabangId}");
                    Log::info("Stok: {$stokSebelum} ➕ {$qty} = {$produkCabang->stok}");
                }

                // 🚩 Tandai sudah diretur (jika kolom tersedia)
                if (Schema::hasColumn('orderans', 'is_returned')) {
                    $order->update([
                        'is_returned' => true,
                        'returned_at' => now(),
                    ]);
                }
            });

            return back()->with('success', 'Barang dari order ini berhasil diretur ke stok cabang aktif.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }
}
