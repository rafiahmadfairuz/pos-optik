<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('supplier')->latest()->get();

        return view('TransferBarang.listPembelian', compact('pembelians'));
    }

    public function detail($id)
    {
        // Perbaikan double query dari kode lama
        $pembelian = Pembelian::with(['items.itemable', 'supplier'])->findOrFail($id);

        return view('TransferBarang.detailPembelian', compact('pembelian'));
    }

    public function retur($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // 1. Lock record pembelian untuk mencegah race condition / double submit
                $pembelian = Pembelian::where('id', $id)->lockForUpdate()->firstOrFail();

                // Validation check
                if ($pembelian->retur) {
                    throw new \Exception("Transaksi pembelian ini sudah pernah diretur sebelumnya.");
                }

                // 2. Loop semua item pembelian
                foreach ($pembelian->items as $item) {
                    // Cari stok produk terkait di Gudang Utama (cabang_id = 0)
                    $produkCabang = ProdukCabang::where('cabang_id', 0)
                        ->where('itemable_type', $item->itemable_type)
                        ->where('itemable_id', $item->itemable_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$produkCabang) {
                        throw new \Exception("Data stok untuk produk ID {$item->itemable_id} ({$item->itemable_type}) tidak ditemukan di Gudang Utama.");
                    }

                    // Cek ketersediaan stok di Gudang Utama sebelum dikurangi
                    if ($produkCabang->stok < $item->quantity) {
                        $namaProduk = $item->itemable->merk ?? $item->itemable->nama ?? 'Produk';
                        throw new \Exception("Stok Gudang Utama tidak mencukupi untuk meretur {$namaProduk}. (Stok Ada: {$produkCabang->stok}, Butuh Retur: {$item->quantity})");
                    }

                    // 3. Kurangi stok di Gudang Utama (cabang_id = 0)
                    $sebelum = $produkCabang->stok;
                    $produkCabang->decrement('stok', $item->quantity);
                    $setelah = $produkCabang->stok;

                    // Audit Logging
                    Log::info("🔁 RETUR SUPPLIER - Pembelian #{$pembelian->id}");
                    Log::info("📦 Produk: {$item->itemable_type} (ID: {$item->itemable_id})");
                    Log::info("📊 Stok Gudang Utama (Cabang 0): {$sebelum} ➖ {$item->quantity} = {$setelah}");
                }

                // 4. Update status retur
                $pembelian->update(['retur' => true]);

                Log::info("✅ RETUR BERHASIL - Pembelian #{$pembelian->id} berhasil diretur total.");
            });

            return back()->with('success', 'Pembelian berhasil diretur. Stok Gudang Utama telah disesuaikan.');

        } catch (\Exception $e) {
            Log::error("❌ RETUR GAGAL - Pembelian #{$id}: " . $e->getMessage());
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }
}
