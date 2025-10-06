<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Softlen;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SoftlensController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        if ($user->role === 'gudang_utama') {
            // Gudang utama -> ambil stok master
            $query = Softlen::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('warna', 'like', "%{$search}%");
                });
            }

            $softlens = $query->get();
        } else {
            // Cabang -> ambil stok dari produk_cabangs
            $cabangId = session('cabang_id');

            $query = ProdukCabang::where('cabang_id', $cabangId)
                ->where('itemable_type', 'softlens')
                ->with('itemable');

            if ($search) {
                $query->whereHasMorph('itemable', [\App\Models\Softlen::class], function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('warna', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            $softlens = $query->get()->map(function ($stok) {
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

        return view('Inventory.softlens', compact('softlens'));
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
                'sku' => 'required|string|max:50|unique:softlens,sku',
                'merk' => 'required|string|max:100',
                'tipe' => 'required|string|max:50',
                'warna' => 'required|string|max:50',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            Softlen::create($validated);

            return redirect()->route('softlens.index')->with('success', 'Softlens berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Create softlens failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan softlens. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $softlens = Softlen::findOrFail($id);

            if (Auth::user()->role === 'gudang_utama') {
                // Update master
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:softlens,sku,' . $id,
                    'merk' => 'required|string|max:100',
                    'tipe' => 'required|string|max:50',
                    'warna' => 'required|string|max:50',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                    'stok' => 'required|integer|min:0',
                ]);

                $softlens->update([
                    ...$validated,
                    'laba' => $validated['harga'] - $validated['harga_beli'],
                ]);
            } else {
                // Update stok cabang
                $validated = $request->validate([
                    'stok' => 'required|integer|min:0',
                ]);

                $cabangId = session('cabang_id');

                $stokCabang = ProdukCabang::firstOrNew([
                    'itemable_id' => $softlens->id,
                    'itemable_type' => 'softlens',
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->qty = $validated['stok'];
                $stokCabang->save();
            }

            return back()->with('success', 'Softlens berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update softlens failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui softlens.')->withInput();
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

            $softlens = Softlen::findOrFail($id);

            // Hapus stok cabang
            ProdukCabang::where('itemable_id', $id)
                ->where('itemable_type', 'softlens')
                ->delete();

            $softlens->delete();

            return back()->with('success', 'Softlens berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Delete softlens failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus softlens.');
        }
    }
}
