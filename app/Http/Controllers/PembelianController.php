<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
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

        return view('TransferBarang.detailPembelian', compact('pembelian'));
    }
    public function retur($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        if ($pembelian->retur) {
            return back()->with('warning', 'Sudah retur sebelumnya.');
        }

        $item = $pembelian->itemable;

        if ($item && isset($item->stok)) {
            $sebelum = $item->stok;
            $setelah = $item->stok - $pembelian->quantity;

            if ($setelah < 0) {
                return back()->with('error', 'Retur gagal: stok tidak mencukupi.');
            }

            Log::info("🔁 RETUR PEMBELIAN - ID: {$pembelian->id}");
            Log::info("📦 Item: " . class_basename($item) . " (ID: {$item->id})");
            Log::info("📊 Stok sebelum retur: {$sebelum}");
            Log::info("➖ Jumlah dikurangi: {$pembelian->quantity}");
            Log::info("✅ Stok setelah retur: {$setelah}");

            $item->decrement('stok', $pembelian->quantity);
            $pembelian->update([
                'retur' => true,
            ]);

            return back()->with('success', 'Pembelian berhasil diretur dan stok dikurangi.');
        }

        return back()->with('error', 'Retur gagal: item tidak memiliki stok atau tidak valid.');
    }
}
