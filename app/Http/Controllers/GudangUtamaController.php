<?php

namespace App\Http\Controllers;

use App\Models\ProdukCabang;
use App\Models\Transfer;
use App\Models\TransferItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $query = Transfer::with(['fromCabang', 'toCabang'])->latest();

        if (session()->has('cabang_id')) {
            $cabangId = session('cabang_id');
            $query->where(function ($q) use ($cabangId) {
                $q->where('from_cabang_id', $cabangId)
                  ->orWhere('to_cabang_id', $cabangId);
            });
        }

        $transfers = $query->get();

        return view('TransferBarang.listTransfer', compact('transfers'));
    }

    public function detailListTransferBarangKeCabang($id)
    {
        $transfer = Transfer::with(['items.itemable', 'fromCabang', 'toCabang'])->findOrFail($id);

        return view('TransferBarang.detailTransfer', compact('transfer'));
    }

    public function retur($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                // 1. Lock data asal untuk cegah race condition & double action
                $originalTransfer = Transfer::where('id', $id)
                    ->lockForUpdate()
                    ->with('items')
                    ->firstOrFail();

                // 2. CEK CELAH: Hanya transaksi status 'completed' yang boleh di-retur
                if ($originalTransfer->status !== 'completed') {
                    throw new \Exception("Transaksi ini sudah tidak aktif (Status saat ini: {$originalTransfer->status}).");
                }

                // 3. KUNCI TRANSAKSI ASAL (Update status jadi 'returned')
                $originalTransfer->update(['status' => 'returned']);

                // 4. BUAT DOKUMEN TRANSAKSI RETUR BARU
                $returTransfer = Transfer::create([
                    'from_cabang_id' => $originalTransfer->to_cabang_id,
                    'to_cabang_id'   => 0, // 0 = Gudang Utama
                    'tanggal'        => now(),
                    'kode'           => 'RTR-' . strtoupper(Str::random(8)),
                    'status'         => 'completed', // Transaksi retur ke Gudang Utama ini kini completed (selesai)
                ]);

                // 5. MUTASI STOK & SALIN ITEM
                foreach ($originalTransfer->items as $item) {
                    // Potong Stok di Cabang Asal
                    $stokCabang = ProdukCabang::where('cabang_id', $originalTransfer->to_cabang_id)
                        ->where('itemable_type', $item->itemable_type)
                        ->where('itemable_id', $item->itemable_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$stokCabang || $stokCabang->stok < $item->quantity) {
                        throw new \Exception("Stok di cabang tidak mencukupi untuk melakukan retur.");
                    }

                    $stokCabang->decrement('stok', $item->quantity);

                    // Tambah Stok di Gudang Utama (Cabang ID 0)
                    $stokGudang = ProdukCabang::where('cabang_id', 0)
                        ->where('itemable_type', $item->itemable_type)
                        ->where('itemable_id', $item->itemable_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stokGudang) {
                        $stokGudang->increment('stok', $item->quantity);
                    } else {
                        ProdukCabang::create([
                            'cabang_id'     => 0,
                            'itemable_type' => $item->itemable_type,
                            'itemable_id'   => $item->itemable_id,
                            'stok'          => $item->quantity,
                        ]);
                    }

                    // Buat Record Item
                    TransferItem::create([
                        'transfer_id'   => $returTransfer->id,
                        'itemable_type' => $item->itemable_type,
                        'itemable_id'   => $item->itemable_id,
                        'quantity'      => $item->quantity,
                        'price'         => $item->price,
                    ]);
                }

                return back()->with('success', 'Proses retur barang ke Gudang Utama berhasil diproses.');
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }

    public function transferKeCabangLain(Request $request, $id)
    {
        $validated = $request->validate([
            'target_cabang_id' => 'required|integer|min:1|max:4',
        ]);

        try {
            return DB::transaction(function () use ($validated, $id) {
                // 1. Lock data asal
                $originalTransfer = Transfer::where('id', $id)
                    ->lockForUpdate()
                    ->with('items')
                    ->firstOrFail();

                // 2. CEK CELAH: Transaksi asal harus 'completed'
                if ($originalTransfer->status !== 'completed') {
                    throw new \Exception("Transaksi ini sudah tidak aktif (Status saat ini: {$originalTransfer->status}).");
                }

                $sourceCabangId = (int) $originalTransfer->to_cabang_id;
                $targetCabangId = (int) $validated['target_cabang_id'];

                if ($sourceCabangId === $targetCabangId) {
                    throw new \Exception("Cabang tujuan tidak boleh sama dengan cabang asal barang saat ini.");
                }

                // 3. KUNCI TRANSAKSI ASAL (Update status jadi 'transferred_out')
                $originalTransfer->update(['status' => 'transferred_out']);

                // 4. BUAT TRANSAKSI TRANSFER BARU
                $newTransfer = Transfer::create([
                    'from_cabang_id' => $sourceCabangId,
                    'to_cabang_id'   => $targetCabangId,
                    'tanggal'        => now(),
                    'kode'           => 'TF-' . strtoupper(Str::random(8)),
                    'status'         => 'completed', // Transaksi baru aktif di cabang tujuan baru
                ]);

                // 5. MUTASI STOK & SALIN ITEM
                foreach ($originalTransfer->items as $ti) {
                    // Potong stok cabang asal
                    $stokSource = ProdukCabang::where('cabang_id', $sourceCabangId)
                        ->where('itemable_type', $ti->itemable_type)
                        ->where('itemable_id', $ti->itemable_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$stokSource || $stokSource->stok < $ti->quantity) {
                        throw new \Exception("Stok di cabang asal tidak mencukupi untuk dialihkan.");
                    }

                    $stokSource->decrement('stok', $ti->quantity);

                    // Tambah stok cabang tujuan
                    $stokTarget = ProdukCabang::where('cabang_id', $targetCabangId)
                        ->where('itemable_type', $ti->itemable_type)
                        ->where('itemable_id', $ti->itemable_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stokTarget) {
                        $stokTarget->increment('stok', $ti->quantity);
                    } else {
                        ProdukCabang::create([
                            'cabang_id'     => $targetCabangId,
                            'itemable_type' => $ti->itemable_type,
                            'itemable_id'   => $ti->itemable_id,
                            'stok'          => $ti->quantity,
                        ]);
                    }

                    TransferItem::create([
                        'transfer_id'   => $newTransfer->id,
                        'itemable_type' => $ti->itemable_type,
                        'itemable_id'   => $ti->itemable_id,
                        'quantity'      => $ti->quantity,
                        'price'         => $ti->price,
                    ]);
                }

                return back()->with('success', 'Transfer barang antar cabang berhasil diproses.');
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses transfer: ' . $e->getMessage());
        }
    }
}
