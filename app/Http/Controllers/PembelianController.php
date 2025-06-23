<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
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
        $pembelian = Pembelian::with(['itemable', 'supplier'])->findOrFail($id);
        $pembelian = Pembelian::with(['items.itemable', 'supplier'])->findOrFail($id);

        return view('TransferBarang.detailPembelian', compact('pembelian'));
    }
    public function retur($id)
    {
        $pembelian = Pembelian::with('items.itemable')->findOrFail($id);

        if ($pembelian->retur) {
            return back()->with('warning', 'Sudah retur sebelumnya.');
        }

        try {
            DB::transaction(function () use ($pembelian) {
                foreach ($pembelian->items as $item) {
                    $produk = $item->itemable;

                    if (!$produk || !isset($produk->stok)) {
                        throw new \Exception("Item tidak valid atau tidak memiliki stok.");
                    }

                    $sebelum = $produk->stok;
                    $setelah = $sebelum - $item->quantity;

                    if ($setelah < 0) {
                        throw new \Exception("Stok tidak cukup untuk produk {$produk->merk}.");
                    }

                    // Kurangi stok
                    $produk->decrement('stok', $item->quantity);

                    // Log per item (nanti kalau berhasil semua akan dicetak)
                    Log::info("🔁 RETUR ITEM - Pembelian #{$pembelian->id}");
                    Log::info("📦 Produk: " . class_basename($produk) . " (ID: {$produk->id})");
                    Log::info("📊 Stok: {$sebelum} ➖ {$item->quantity} = {$setelah}");
                }

                // Tandai retur jika semua berhasil
                $pembelian->update(['retur' => true]);

                // Log setelah semua selesai
                Log::info("✅ RETUR BERHASIL - Pembelian #{$pembelian->id} ditandai retur.");
            });

            return back()->with('success', 'Pembelian berhasil diretur. Semua stok dikurangi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }
}
