<?php

namespace App\Http\Controllers\Inventory;

use App\Models\LensaFinish;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LensaFinishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        if ($user->role === 'gudang_utama') {
            // Gudang utama lihat stok master
            $query = LensaFinish::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('desain', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                });
            }

            $lensaFinish = $query->get();
        } else {
            // Cabang → ambil dari produk_cabangs
            $cabangId = session('cabang_id');

            $query = ProdukCabang::where('cabang_id', $cabangId)
                ->where('itemable_type', 'lensa_finish')
                ->with('itemable');

            if ($search) {
                $query->whereHasMorph('itemable', [\App\Models\LensaFinish::class], function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('desain', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            $lensaFinish = $query->get()->map(function ($stok) {
                return (object) [
                    'id' => $stok->itemable->id,
                    'sku' => $stok->itemable->sku,
                    'merk' => $stok->itemable->merk,
                    'desain' => $stok->itemable->desain,
                    'tipe' => $stok->itemable->tipe,
                    'sph' => $stok->itemable->sph,
                    'cyl' => $stok->itemable->cyl,
                    'add' => $stok->itemable->add,
                    'harga' => $stok->itemable->harga,
                    'harga_beli' => $stok->itemable->harga_beli,
                    'laba' => $stok->itemable->laba,
                    'stok' => $stok->stok, // stok cabang
                ];
            });
        }

        return view('Inventory.lensaFinish', compact('lensaFinish'));
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
                'sku' => 'required|string|max:50|unique:lensa_finishes,sku',
                'merk' => 'required|string|max:100',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'stok' => 'required|integer|min:0',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            LensaFinish::create($validated);

            return redirect()->route('lensaFinish.index')->with('success', 'Lensa berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Create lensa failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan lensa. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $lensa = LensaFinish::findOrFail($id);

            if (Auth::user()->role === 'gudang_utama') {
                // Update master
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:lensa_finishes,sku,' . $id,
                    'merk' => 'required|string|max:100',
                    'desain' => 'required|string|max:50',
                    'tipe' => 'required|string|max:50',
                    'sph' => 'required|numeric',
                    'cyl' => 'required|numeric',
                    'add' => 'nullable|numeric',
                    'stok' => 'required|integer|min:0',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                ]);

                $lensa->update([
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
                    'itemable_id' => $lensa->id,
                    'itemable_type' => 'lensa_finish',
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->qty = $validated['stok'];
                $stokCabang->save();
            }

            return redirect()->back()->with('success', 'Lensa berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update lensa failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui lensa.')->withInput();
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

            $lensa = LensaFinish::findOrFail($id);

            // Hapus stok cabang terkait
            ProdukCabang::where('itemable_id', $id)
                ->where('itemable_type', 'lensa_finish')
                ->delete();

            $lensa->delete();

            return redirect()->back()->with('success', 'Lensa berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Delete lensa failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus lensa.');
        }
    }
}
