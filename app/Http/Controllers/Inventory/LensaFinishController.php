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
        $search = $request->input('search');
        $cabangId = in_array(Auth::user()->role, ['gudang_utama', 'admin']) ? 0 : session('cabang_id');

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
            if (!$stok->itemable) {
                return null;
            }

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
                'stok' => $stok->stok,
            ];
        })->filter();

        return view('Inventory.lensaFinish', compact('lensaFinish'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['gudang_utama', 'admin'])) {
                return back()->with('error', 'Hanya gudang utama dan admin yang bisa menambahkan produk baru.');
            }

            $validated = $request->validate([
                'sku' => 'required|string|max:50|unique:lensa_finishes,sku',
                'merk' => 'required|string|max:100',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            $lensa = LensaFinish::create($validated);

            $cabangIds = [0, 1, 2, 3];
            $dataCabang = [];

            foreach ($cabangIds as $cabangId) {
                $dataCabang[] = [
                    'itemable_type' => 'lensa_finish',
                    'itemable_id' => $lensa->id,
                    'cabang_id' => $cabangId,
                    'stok' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ProdukCabang::insert($dataCabang);

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

            if (in_array(Auth::user()->role, ['gudang_utama', 'admin'])) {
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:lensa_finishes,sku,' . $id,
                    'merk' => 'required|string|max:100',
                    'desain' => 'required|string|max:50',
                    'tipe' => 'required|string|max:50',
                    'sph' => 'required|numeric',
                    'cyl' => 'required|numeric',
                    'add' => 'nullable|numeric',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                ]);

                $lensa->update([
                    'sku' => $validated['sku'],
                    'merk' => $validated['merk'],
                    'desain' => $validated['desain'],
                    'tipe' => $validated['tipe'],
                    'sph' => $validated['sph'],
                    'cyl' => $validated['cyl'],
                    'add' => $validated['add'] ?? null,
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
                    'itemable_id' => $lensa->id,
                    'itemable_type' => 'lensa_finish',
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->stok = $validated['stok'];
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
            if (!in_array(Auth::user()->role, ['gudang_utama', 'admin'])) {
                return back()->with('error', 'Hanya gudang utama dan admin yang bisa menghapus produk.');
            }

            $lensa = LensaFinish::findOrFail($id);

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