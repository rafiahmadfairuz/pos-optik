<?php

namespace App\Http\Controllers\Inventory;

use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LensaKhususController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cabangId = in_array(Auth::user()->role, ['gudang_utama', 'admin']) ? 0 : session('cabang_id');

        $query = ProdukCabang::where('cabang_id', $cabangId)
            ->where('itemable_type', 'lensa_khusus')
            ->with('itemable');

        if ($search) {
            $query->whereHasMorph('itemable', [\App\Models\LensaKhusus::class], function ($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                    ->orWhere('desain', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $lensaKhusus = $query->get()->map(function ($stok) {
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
                'estimasi_selesai_hari' => $stok->itemable->estimasi_selesai_hari,
                'status_pesanan' => $stok->itemable->status_pesanan,
                'stok' => $stok->stok,
            ];
        })->filter();

        return view('Inventory.lensaKhusus', compact('lensaKhusus'));
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
                'sku' => 'required|string|max:50|unique:lensa_khususes,sku',
                'merk' => 'required|string|max:100',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'estimasi_selesai_hari' => 'nullable|integer|min:1',
                'status_pesanan' => 'required|string|in:menunggu,proses,selesai',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            $lensa = LensaKhusus::create($validated);

            $cabangIds = [0, 1, 2, 3];
            $dataCabang = [];

            foreach ($cabangIds as $cabangId) {
                $dataCabang[] = [
                    'itemable_type' => 'lensa_khusus',
                    'itemable_id' => $lensa->id,
                    'cabang_id' => $cabangId,
                    'stok' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ProdukCabang::insert($dataCabang);

            return redirect()->route('lensaKhusus.index')->with('success', 'Lensa Khusus berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Create lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan lensa khusus. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $lensa = LensaKhusus::findOrFail($id);

            if (in_array(Auth::user()->role, ['gudang_utama', 'admin'])) {
                $validated = $request->validate([
                    'sku' => 'required|string|max:50|unique:lensa_khususes,sku,' . $id,
                    'merk' => 'required|string|max:100',
                    'desain' => 'required|string|max:50',
                    'tipe' => 'required|string|max:50',
                    'sph' => 'required|numeric',
                    'cyl' => 'required|numeric',
                    'add' => 'nullable|numeric',
                    'harga_beli' => 'required|numeric|min:0',
                    'harga' => 'required|numeric|min:0',
                    'estimasi_selesai_hari' => 'nullable|integer|min:1',
                    'status_pesanan' => 'required|string|in:menunggu,proses,selesai',
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
                    'estimasi_selesai_hari' => $validated['estimasi_selesai_hari'] ?? null,
                    'status_pesanan' => $validated['status_pesanan'],
                ]);
            } else {
                $validated = $request->validate([
                    'stok' => 'required|integer|min:0',
                ]);

                $cabangId = session('cabang_id');

                $stokCabang = ProdukCabang::firstOrNew([
                    'itemable_id' => $lensa->id,
                    'itemable_type' => 'lensa_khusus',
                    'cabang_id' => $cabangId,
                ]);

                $stokCabang->stok = $validated['stok'];
                $stokCabang->save();
            }

            return back()->with('success', 'Lensa Khusus berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui lensa khusus.')->withInput();
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

            $lensa = LensaKhusus::findOrFail($id);

            ProdukCabang::where('itemable_id', $id)
                ->where('itemable_type', 'lensa_khusus')
                ->delete();

            $lensa->delete();

            return back()->with('success', 'Lensa Khusus berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Delete lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus lensa khusus.');
        }
    }
}