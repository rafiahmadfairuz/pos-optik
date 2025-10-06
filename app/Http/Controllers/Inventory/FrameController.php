<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Frame;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        if ($user->role === 'gudang_utama') {
            // Gudang utama -> lihat stok master
            $query = Frame::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                });
            }

            $frame = $query->get();
        } else {
            // Cabang -> ambil stok dari produk_cabangs
            $cabangId = session('cabang_id');

            $query = ProdukCabang::where('cabang_id', $cabangId)
                ->where('itemable_type', 'frame')
                ->with('itemable');

            if ($search) {
                $query->whereHasMorph('itemable', [\App\Models\Frame::class], function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            $frame = $query->get()->map(function ($stok) {
                return (object) [
                    'id' => $stok->itemable->id,
                    'sku' => $stok->itemable->sku,
                    'merk' => $stok->itemable->merk,
                    'tipe' => $stok->itemable->tipe,
                    'warna' => $stok->itemable->warna,
                    'harga' => $stok->itemable->harga,
                    'harga_beli' => $stok->itemable->harga_beli,
                    'laba' => $stok->itemable->laba,
                    'stok' => $stok->stok, // stok cabang
                ];
            });
        }

        return view('Inventory.frame', compact('frame'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (Auth::user()->role !== 'gudang_utama') {
                return back()->with('error', 'Hanya gudang utama yang bisa menambahkan produk baru.');
            }

            $validated = $request->validate([
                'sku' => 'required|string|max:50|unique:frames,sku',
                'merk' => 'required|string|max:100',
                'tipe' => 'required|string|max:50',
                'warna' => 'required|string|max:50',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            Frame::create($validated);

            return redirect()->route('frame.index')->with('success', 'Frame berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Create frame failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan frame. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $frame = Frame::findOrFail($id);

            if (Auth::user()->role === 'gudang_utama') {
                // Gudang utama update master
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:frames,sku,' . $id,
                    'merk' => 'required|string|max:100',
                    'tipe' => 'required|string|max:50',
                    'warna' => 'required|string|max:50',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                    'stok' => 'required|integer|min:0',
                ]);

                $frame->update([
                    ...$validated,
                    'laba' => $validated['harga'] - $validated['harga_beli'],
                ]);
            } else {
                // Cabang hanya update stok di produk_cabangs
                $validated = $request->validate([
                    'stok' => 'required|integer|min:0',
                ]);

                $cabangId = session('cabang_id');

                $stokCabang = ProdukCabang::firstOrNew([
                    'itemable_id' => $frame->id,
                    'itemable_type' => Frame::class,
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->qty = $validated['stok'];
                $stokCabang->save();
            }

            return back()->with('success', 'Frame berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update frame failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui frame.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (Auth::user()->role !== 'gudang_utama') {
                return back()->with('error', 'Hanya gudang utama yang bisa menghapus produk.');
            }

            $frame = Frame::findOrFail($id);

            // Hapus stok cabang terkait
            ProdukCabang::where('itemable_id', $id)
                ->where('itemable_type', 'frame')
                ->delete();

            $frame->delete();

            return back()->with('success', 'Frame berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Delete frame failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus frame.');
        }
    }
}
