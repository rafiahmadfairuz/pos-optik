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
        $search = $request->input('search');
        $cabangId = Auth::user()->role === 'gudang_utama' ? 0 : session('cabang_id');

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
            if (!$stok->itemable) {
                return null;
            }

            return (object) [
                'id' => $stok->itemable->id,
                'sku' => $stok->itemable->sku,
                'merk' => $stok->itemable->merk,
                'tipe' => $stok->itemable->tipe,
                'warna' => $stok->itemable->warna,
                'harga' => $stok->itemable->harga,
                'harga_beli' => $stok->itemable->harga_beli,
                'laba' => $stok->itemable->laba,
                'stok' => $stok->stok,
            ];
        })->filter();

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
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            $frame = Frame::create($validated);

            $cabangIds = [0, 1, 2, 3];
            $dataCabang = [];

            foreach ($cabangIds as $cabangId) {
                $dataCabang[] = [
                    'itemable_type' => 'frame',
                    'itemable_id' => $frame->id,
                    'cabang_id' => $cabangId,
                    'stok' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ProdukCabang::insert($dataCabang);

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

            if (Auth::user()->role === 'gudang_utama' || Auth::user()->role === 'admin') {
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:frames,sku,' . $id,
                    'merk' => 'required|string|max:100',
                    'tipe' => 'required|string|max:50',
                    'warna' => 'required|string|max:50',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                ]);

                $frame->update([
                    'sku' => $validated['sku'],
                    'merk' => $validated['merk'],
                    'tipe' => $validated['tipe'],
                    'warna' => $validated['warna'],
                    'harga_beli' => $validated['harga_beli'],
                    'harga' => $validated['harga'],
                    'laba' => $validated['harga'] - $validated['harga_beli'],
                ]);
            } else {
                $validated = $request->validate([
                    'stok' => 'required|integer|min:0',
                ]);

                $cabangId = session('cabang_id');

                $stokCabang = ProdukCabang::firstOrNew([
                    'itemable_id' => $frame->id,
                    'itemable_type' => 'frame',
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->stok = $validated['stok'];
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
          if (!in_array(Auth::user()->role, ['gudang_utama', 'admin'])) {
                return back()->with('error', 'Hanya gudang utama yang bisa menghapus produk.');
            }

            $frame = Frame::findOrFail($id);

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