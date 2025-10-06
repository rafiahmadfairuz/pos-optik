<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

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
                    $aliasType = $item->itemable_type; // 🧠 alias morphMap ('frame', 'accessory', dst)
                    $modelClass = Relation::getMorphedModel($aliasType); // dapetin class asli
                    if (!$modelClass) {
                        throw new \Exception("Tipe produk tidak dikenali: {$aliasType}");
                    }

                    // 🔍 Ambil produk dari gudang utama
                    $produkGudang = $modelClass::find($item->itemable_id);
                    if (!$produkGudang) {
                        throw new \Exception("Produk tidak ditemukan di gudang utama.");
                    }

                    // 🔍 Ambil stok di cabang dari tabel produk_cabangs
                    $produkCabang = ProdukCabang::where('cabang_id', $transfer->cabang_id)
                        ->where('itemable_id', $item->itemable_id)
                        ->where('itemable_type', $aliasType)
                        ->first();

                    if (!$produkCabang) {
                        throw new \Exception("Produk tidak ditemukan di cabang {$transfer->cabang_id}.");
                    }

                    $qty = $item->quantity;

                    // 🚫 Validasi stok cabang cukup
                    if ($produkCabang->stok < $qty) {
                        throw new \Exception("Stok cabang tidak cukup untuk retur produk: " . ($produkGudang->sku ?? $produkGudang->merk ?? ''));
                    }

                    $stokCabangSebelum = $produkCabang->stok;
                    $stokGudangSebelum = $produkGudang->stok;

                    // 🔻 Kurangi stok di cabang
                    $produkCabang->decrement('stok', $qty);

                    // 🔺 Tambah stok di gudang utama
                    $produkGudang->increment('stok', $qty);

                    // 🧾 Logging detail
                    Log::info("=== RETUR Transfer ID {$transfer->id} - Produk: {$produkGudang->sku} ===");
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

                // ✅ Tandai retur
                $transfer->update(['retur' => true]);
                Log::info("=== RETUR BERHASIL - Transfer #{$transfer->id} ditandai retur ===");
            });

            return back()->with('success', 'Transfer berhasil diretur.');
        } catch (\Exception $e) {
            Log::error("[RETUR GAGAL] {$e->getMessage()}");
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }

    public function transferKeCabangLain(Request $request, $id)
    {
        $validated = $request->validate([
            'target_cabang_id' => 'required|integer|exists:cabangs,id',
        ]);

        $targetCabangId = (int) $validated['target_cabang_id'];

        // 🔍 Ambil transfer utama dan semua item
        $transfer = Transfer::with(['items.itemable'])->findOrFail($id);
        $sourceCabangId = (int) $transfer->cabang_id;

        if ($targetCabangId === $sourceCabangId) {
            return back()->with('warning', 'Cabang tujuan sama dengan cabang sumber.');
        }

        try {
            DB::transaction(function () use ($transfer, $sourceCabangId, $targetCabangId) {
                foreach ($transfer->items as $ti) {
                    $aliasType = $ti->itemable_type; // contoh: 'frame', 'softlens', 'accessory'
                    $modelClass = Relation::getMorphedModel($aliasType); // ambil class aslinya
                    if (!$modelClass) {
                        throw new \Exception("Tipe produk tidak dikenali: {$aliasType}");
                    }

                    // 🔍 Produk acuan (template) di gudang utama
                    $template = $modelClass::find($ti->itemable_id);
                    if (!$template) {
                        throw new \Exception("Produk acuan tidak ditemukan untuk item #{$ti->id}.");
                    }

                    // 🔍 Ambil stok di cabang sumber
                    $produkSource = ProdukCabang::where('itemable_id', $ti->itemable_id)
                        ->where('itemable_type', $aliasType)
                        ->where('cabang_id', $sourceCabangId)
                        ->first();

                    if (!$produkSource) {
                        throw new \Exception("Produk tidak ditemukan di cabang sumber (#{$sourceCabangId}).");
                    }

                    $qty = (int) $ti->quantity;

                    if ($produkSource->stok < $qty) {
                        $nama = $template->nama ?? $template->merk ?? $template->sku ?? 'Produk';
                        throw new \Exception("Stok cabang sumber tidak cukup untuk {$nama} (butuh {$qty}, ada {$produkSource->stok}).");
                    }

                    // 🔻 Kurangi stok di cabang sumber
                    $produkSource->decrement('stok', $qty);

                    // 🔍 Ambil stok di cabang tujuan
                    $produkTarget = ProdukCabang::where('itemable_id', $ti->itemable_id)
                        ->where('itemable_type', $aliasType)
                        ->where('cabang_id', $targetCabangId)
                        ->first();

                    if ($produkTarget) {
                        // 🔁 Produk sudah ada → tambahkan stok
                        $produkTarget->increment('stok', $qty);
                    } else {
                        // 🆕 Belum ada → buat record baru
                        ProdukCabang::create([
                            'itemable_id'   => $ti->itemable_id,
                            'itemable_type' => $aliasType,
                            'cabang_id'     => $targetCabangId,
                            'stok'          => $qty,
                        ]);
                    }

                    // 🧾 Logging detail
                    $skuLog = $template->sku ?? ($template->nama ?? 'unknown');
                    Log::info("[CABANG→CABANG] Transfer #{$transfer->kode} | Item #{$ti->id} ({$skuLog}) | {$sourceCabangId} -> {$targetCabangId} | qty {$qty}");
                }

                // ✅ Update header transfer (pindah cabang)
                $transfer->update([
                    'cabang_id' => $targetCabangId,
                    'tanggal'   => now()->toDateString(),
                ]);
            });

            return back()->with('success', 'Transfer antar cabang berhasil diproses.');
        } catch (\Throwable $e) {
            Log::error("[TRANSFER CABANG GAGAL] {$e->getMessage()}");
            return back()->with('error', 'Gagal memproses transfer: ' . $e->getMessage());
        }
    }
}
