<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Accessories;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccessoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
    {
        $search = $request->input('search');
        $cabangId = Auth::user()->role === 'gudang_utama' ? 0 : session('cabang_id');

        $query = ProdukCabang::where('cabang_id', $cabangId)
            ->where('itemable_type', 'accessory')
            ->with('itemable');

        if ($search) {
            $query->whereHasMorph('itemable', [\App\Models\Accessories::class], function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $accessories = $query->get()->map(function ($stok) {
            if (!$stok->itemable) {
                return null;
            }

            return (object) [
                'id' => $stok->itemable->id,
                'sku' => $stok->itemable->sku,
                'nama' => $stok->itemable->nama,
                'jenis' => $stok->itemable->jenis,
                'harga' => $stok->itemable->harga,
                'harga_beli' => $stok->itemable->harga_beli,
                'laba' => $stok->itemable->laba,
                'stok' => $stok->stok,
            ];
        })->filter();

        return view('Inventory.accesories', compact('accessories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['gudang_utama', 'admin'])) {
                return back()->with('error', 'Hanya gudang utama yang bisa menambahkan produk baru.');
            }

            $validated = $request->validate([
                'sku' => 'required|string|max:50|unique:accessories,sku',
                'nama' => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            $accessory = Accessories::create($validated);

            $cabangIds = [0, 1, 2, 3];
            $dataCabang = [];

            foreach ($cabangIds as $cabangId) {
                $dataCabang[] = [
                    'itemable_type' => 'accessory',
                    'itemable_id' => $accessory->id,
                    'cabang_id' => $cabangId,
                    'stok' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ProdukCabang::insert($dataCabang);

            return back()->with('success', 'Aksesori berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Create accessory failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $accessory = Accessories::findOrFail($id);

            if (Auth::user()->role === 'gudang_utama' || Auth::user()->role === 'admin') {
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:accessories,sku,' . $id,
                    'nama' => 'required|string|max:100',
                    'jenis' => 'required|string|max:50',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                ]);

                $accessory->update([
                    ...$validated,
                    'laba' => $validated['harga'] - $validated['harga_beli'],
                ]);
            } else {
                $validated = $request->validate([
                    'stok' => 'required|integer|min:0',
                ]);

                $cabangId = session('cabang_id');

                $stokCabang = ProdukCabang::firstOrNew([
                    'itemable_id' => $accessory->id,
                    'itemable_type' => 'accessory',
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->stok = $validated['stok'];
                $stokCabang->save();
            }

            return back()->with('success', 'Aksesori berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update accessory failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui aksesori.')->withInput();
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

            $accessory = Accessories::findOrFail($id);

            ProdukCabang::where('itemable_id', $id)
                ->where('itemable_type', 'accessory')
                ->delete();

            $accessory->delete();

            return back()->with('success', 'Aksesori berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Delete accessory failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus aksesori.')->withInput();
        }
    }
}