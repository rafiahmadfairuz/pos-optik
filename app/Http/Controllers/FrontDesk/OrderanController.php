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
            ->with('user')
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
        $order = Orderan::findOrFail($id);
        $isUserAdmin = (Auth::user()->role ?? '') == 'admin';
        $originalOrderStatus = $order->order_status;
        $requestedOrderStatus = $request->input('order_status');

        // Cek Izin Edit Order Complete
        if ($originalOrderStatus == 'complete' && !$isUserAdmin) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah order yang sudah selesai.');
        }

        // Bersihkan input angka nominal
        $customerPayingCleaned = (float) preg_replace('/[^\d]/', '', (string) $request->input('customer_paying'));
        $diskonCleaned = (float) preg_replace('/[^\d]/', '', (string) $request->input('diskon'));

        $request->merge([
            'customer_paying_cleaned' => $customerPayingCleaned,
            'diskon_cleaned' => $diskonCleaned,
        ]);

        // Validasi HANYA untuk transaksi (Tidak ada lagi validasi resep disini)
        $validatedData = $request->validate([
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
            'diskon_cleaned' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Recalculate Total Items
            $calculatedTotal = $order->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            // Get Asuransi Nominal
            $asuransiNominal = 0;
            if ($validatedData['payment_type'] == 'asuransi' && isset($validatedData['asuransi_id'])) {
                $asuransi = Asuransi::find($validatedData['asuransi_id']);
                if ($asuransi) {
                    $asuransiNominal = $asuransi->nominal;
                }
            }

            $diskon = $validatedData['diskon_cleaned'] ?? 0;
            $perluDibayar = max($calculatedTotal - $asuransiNominal - $diskon, 0);

            // Perhitungan Akumulasi Pembayaran / Kurang Bayar
            $customerPayingAkhir = $order->customer_paying + $validatedData['customer_paying_cleaned'];
            $kurangBayar = max($perluDibayar - $customerPayingAkhir, 0);
            $kembalian = max($customerPayingAkhir - $perluDibayar, 0);

            // LOGIC PENGURANGAN STOK SAAT STATUS PENDING -> COMPLETE
            if ($requestedOrderStatus == 'complete' && $originalOrderStatus != 'complete') {
                $currentCabangId = session('cabang_id');

                foreach ($order->items as $item) {
                    $itemableType = $item->itemable_type;
                    $itemableId   = $item->itemable_id;

                    $morphMap = Relation::morphMap();
                    $modelClass = $morphMap[$itemableType] ?? null;

                    if (!$modelClass) {
                        throw new \Exception("Tipe produk {$itemableType} tidak dikenali untuk item ID {$item->id}.");
                    }

                    $produkCabang = ProdukCabang::where('cabang_id', $currentCabangId)
                        ->where('itemable_id', $itemableId)
                        ->where('itemable_type', $itemableType)
                        ->lockForUpdate()
                        ->first();

                    if (!$produkCabang) {
                        throw new \Exception("Produk cabang tidak ditemukan untuk item #{$item->id} ({$itemableType}).");
                    }

                    if ($produkCabang->stok < $item->quantity) {
                        $namaProduk = $produkCabang->itemable->merk ?? $produkCabang->itemable->nama ?? 'Produk';
                        throw new \Exception("Stok {$namaProduk} tidak mencukupi. Diminta {$item->quantity}, tersedia {$produkCabang->stok}.");
                    }

                    // Decrement stok
                    $produkCabang->decrement('stok', $item->quantity);

                    Log::info("[ORDER COMPLETE] Stok cabang dikurangi", [
                        'cabang_id'   => $currentCabangId,
                        'itemable_id' => $itemableId,
                        'itemable_type' => $itemableType,
                        'qty'         => $item->quantity,
                        'stok_sisa'   => $produkCabang->stok,
                    ]);
                }

                $order->complete_date = now();
                if ($order->payment_status == 'unpaid') {
                    $order->payment_status = 'paid';
                }
            }

            // Update Data Orderan
            $order->update([
                'order_date' => $validatedData['order_date'],
                'complete_date' => $order->complete_date ?? $validatedData['complete_date'],
                'staff_id' => $validatedData['staff_id'],
                'payment_type' => $validatedData['payment_type'],
                'asuransi_id' => $validatedData['asuransi_id'],
                'order_status' => $validatedData['order_status'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_status'],
                'customer_paying' => $customerPayingAkhir,
                'diskon' => $diskon,
                'kurang_bayar' => $kurangBayar,
                'total' => $calculatedTotal,
                'perlu_dibayar' => $perluDibayar,
                'kembalian' => $kembalian,
            ]);

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
                    $itemableType = $item->itemable_type;
                    $itemableId   = $item->itemable_id;

                    $morphMap = Relation::morphMap();
                    $modelClass = $morphMap[$itemableType] ?? null;

                    if (!$modelClass) {
                        throw new \Exception("Tipe produk {$itemableType} tidak dikenali.");
                    }

                    $produkCabang = ProdukCabang::where('cabang_id', $currentCabangId)
                        ->where('itemable_id', $itemableId)
                        ->where('itemable_type', $itemableType)
                        ->lockForUpdate()
                        ->first();

                    if (!$produkCabang) {
                        throw new \Exception("Produk cabang tidak ditemukan untuk item #{$item->id} ({$itemableType}).");
                    }

                    $qty = (int) $item->quantity;
                    $stokSebelum = (int) $produkCabang->stok;
                    $produkCabang->increment('stok', $qty);

                    Log::info("=== RETUR ORDER #{$order->id} ===");
                    Log::info("Tipe Produk: {$itemableType}");
                    Log::info("Cabang: {$currentCabangId}");
                    Log::info("Stok: {$stokSebelum} ➕ {$qty} = {$produkCabang->stok}");
                }

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
