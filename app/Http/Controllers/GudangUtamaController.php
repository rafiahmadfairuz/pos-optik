<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;
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

    public function transferKeCabangLain(Request $request, $id)
    {
        $validated = $request->validate([
            'target_cabang_id' => 'required|integer|exists:cabangs,id',
        ]);
        $targetCabangId = (int) $validated['target_cabang_id'];

        $transfer = Transfer::with(['items.itemable'])->findOrFail($id);
        $sourceCabangId = (int) $transfer->cabang_id;

        if ($targetCabangId === $sourceCabangId) {
            return back()->with('warning', 'Cabang tujuan sama dengan cabang saat ini.');
        }

        try {
            DB::transaction(function () use ($transfer, $sourceCabangId, $targetCabangId) {

                foreach ($transfer->items as $ti) {
                    $modelClass = $ti->itemable_type;   // contoh: App\Models\Frame / Lensa / dst
                    $template   = $ti->itemable;        // produk di GUDANG (dipakai sbg template atribut)

                    if (!$template) {
                        throw new \Exception("Produk acuan (gudang) tidak ditemukan untuk item #{$ti->id}.");
                    }

                    $model   = new $modelClass;
                    $columns = Schema::getColumnListing($model->getTable());
                    $match   = ['sku', 'merk', 'tipe', 'warna', 'desain', 'nama', 'jenis', 'sph', 'cyl', 'add'];

                    // 1) Cari produk di CABANG SUMBER (harus ada, karena pernah ditransfer)
                    $qSource = $modelClass::query()->where('cabang_id', $sourceCabangId);
                    foreach ($match as $col) {
                        if (in_array($col, $columns) && isset($template->$col)) {
                            $qSource->where($col, $template->$col);
                        }
                    }
                    $produkSource = $qSource->first();
                    if (!$produkSource) {
                        throw new \Exception("Produk tidak ditemukan di cabang sumber (#{$sourceCabangId}) untuk item #{$ti->id}.");
                    }

                    $qty = (int) $ti->quantity;
                    if ((int)$produkSource->stok < $qty) {
                        $name = $template->nama ?? $template->merk ?? $template->sku ?? 'Produk';
                        throw new \Exception("Stok cabang sumber tidak cukup untuk {$name} (butuh {$qty}, ada {$produkSource->stok}).");
                    }

                    // Kurangi stok cabang sumber
                    $produkSource->decrement('stok', $qty);

                    // 2) Tambahkan ke CABANG TUJUAN (buat baru jika belum ada)
                    $qTarget = $modelClass::query()->where('cabang_id', $targetCabangId);
                    foreach ($match as $col) {
                        if (in_array($col, $columns) && isset($template->$col)) {
                            $qTarget->where($col, $template->$col);
                        }
                    }
                    $produkTarget = $qTarget->first();

                    if ($produkTarget) {
                        $produkTarget->increment('stok', $qty);
                    } else {
                        // clone field dari template (produk gudang) → cabang tujuan
                        $dataBaru = [
                            'cabang_id' => $targetCabangId,
                            'stok'      => $qty,
                        ];

                        $copyable = array_diff(
                            $columns,
                            ['id', 'created_at', 'updated_at', 'stok', 'cabang_id', 'status_pesanan', 'estimasi_selesai_hari']
                        );
                        foreach ($copyable as $col) {
                            if (isset($template->$col)) {
                                $dataBaru[$col] = $template->$col;
                            }
                        }

                        $modelClass::create($dataBaru);
                    }

                    // Logging ringkas
                    $skuLog = $template->sku ?? ($template->nama ?? 'unknown');
                    Log::info("[CABANG→CABANG] Transfer #{$transfer->kode} | Item #{$ti->id} ({$skuLog}) | {$sourceCabangId} -> {$targetCabangId} | qty {$qty}");
                }

                // 3) Update header transfer (tanpa ubah struktur DB)
                $transfer->update([
                    'cabang_id' => $targetCabangId,
                    'tanggal'   => now()->toDateString(), // kolom bertipe DATE
                ]);
            });

            return back()->with('success', 'Transfer antar cabang berhasil diproses & data header diperbarui.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses transfer: ' . $e->getMessage());
        }
    }
}
