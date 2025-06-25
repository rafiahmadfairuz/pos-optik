<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GudangUtamaController extends Controller
{
    public function transferBarangKeCabang()
    {
        return view('TransferBarang.transferBarangKeCabang');
    }
    public function beliBarang()
    {
        return view('TransferBarang.beliBarang');
    }
    public function listTransferBarangKeCabang()
    {
        $query = Transfer::with('cabang')->latest();

        if (session()->has('cabang_id')) {
            $query->where('cabang_id', session('cabang_id'));
        }

        $transfers = $query->get();

        return view('TransferBarang.listTransfer', compact('transfers'));
    }

    public function detailListTransferBarangKeCabang($id)
    {
        $transfer = Transfer::with(['items.itemable', 'cabang'])->findOrFail($id);

        return view('TransferBarang.detailTransfer', compact('transfer'));
    }

    public function retur($id)
    {
        $transfer = Transfer::with('items')->findOrFail($id);

        if ($transfer->retur) {
            return back()->with('warning', 'Transfer ini sudah diretur sebelumnya.');
        }

        try {
            DB::transaction(function () use ($transfer) {
                foreach ($transfer->items as $item) {
                    $modelClass = $item->itemable_type;
                    $model = new $modelClass;
                    $columns = Schema::getColumnListing($model->getTable());

                    $matchableFields = ['sku', 'merk', 'tipe', 'warna', 'desain', 'nama', 'jenis', 'sph', 'cyl', 'add'];

                    $queryCabang = $modelClass::query();
                    foreach ($matchableFields as $field) {
                        if (in_array($field, $columns) && isset($item->itemable->$field)) {
                            $queryCabang->where($field, $item->itemable->$field);
                        }
                    }
                    $queryCabang->where('cabang_id', $transfer->cabang_id);
                    $produkCabang = $queryCabang->first();

                    $queryGudang = $modelClass::query();
                    foreach ($matchableFields as $field) {
                        if (in_array($field, $columns) && isset($item->itemable->$field)) {
                            $queryGudang->where($field, $item->itemable->$field);
                        }
                    }
                    $queryGudang->whereNull('cabang_id');
                    $produkGudang = $queryGudang->first();

                    if (!$produkCabang || !$produkGudang) {
                        throw new \Exception("Produk tidak ditemukan di cabang atau gudang.");
                    }

                    $qty = $item->quantity;
                    $stokCabangSebelum = $produkCabang->stok;
                    $stokGudangSebelum = $produkGudang->stok;

                    if ($stokCabangSebelum < $qty) {
                        throw new \Exception("Stok cabang tidak cukup untuk retur.");
                    }

                    $produkCabang->decrement('stok', $qty);
                    $produkGudang->increment('stok', $qty);

                    Log::info("=== RETUR Transfer ID {$transfer->id} - Produk: {$item->itemable->sku} ===");
                    Log::info("Cabang:");
                    Log::info("  - cabang_id: {$produkCabang->cabang_id}");
                    Log::info("  - stok sebelum: {$stokCabangSebelum}");
                    Log::info("  - stok dikurangi: {$qty}");
                    Log::info("Gudang Utama:");
                    Log::info("  - stok sebelum: {$stokGudangSebelum}");
                    Log::info("  - stok ditambah: {$qty}");
                    Log::info("Setelah retur:");
                    Log::info("  - stok cabang sekarang: {$produkCabang->stok}");
                    Log::info("  - stok gudang sekarang: {$produkGudang->stok}");
                    Log::info("Produk sudah selesai diretur.\n");
                }

                $transfer->update(['retur' => true]);
                Log::info("=== RETUR BERHASIL - Transfer #{$transfer->id} ditandai retur ===");
            });

            return back()->with('success', 'Transfer berhasil diretur.');
        } catch (\Exception $e) {
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }
}
