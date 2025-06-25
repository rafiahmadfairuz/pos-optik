<?php

namespace App\Http\Controllers\Inventory;

use App\Models\LensaKhusus;
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
        $user = Auth::user();
        $search = $request->input('search');

        $query = LensaKhusus::query();

        if ($user->role === 'gudang_utama') {
            $query->whereNull('cabang_id');
        } else {
            $query->where('cabang_id', session('cabang_id'));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('desain', 'like', "%{$search}%");
            });
        }

        $lensaKhusus = $query->get();

        return view('Inventory.lensaKhusus', compact('lensaKhusus'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'sku' => 'required|string|max:50|unique:lensa_khususes,sku',
                'merk' => 'required|string|max:50',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'stok' => 'required|integer|min:0',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'estimasi_selesai_hari' => 'required|integer|nullable',
                'status_pesanan' => 'required',
            ]);

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            LensaKhusus::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return redirect()->route('lensaKhusus.index')->with('success', 'Lensa Khusus berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', implode(' | ', $e->validator->errors()->all()))->withInput();
        } catch (\Exception $e) {
            Log::error('Create lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan lensa khusus. ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rules = [
                'sku' => 'required|string|max:50|unique:lensa_khususes,sku,' . $id,
                'merk' => 'required|string|max:50',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'stok' => 'required|integer|min:0',
                'harga' => 'required|numeric|min:0',
                'estimasi_selesai_hari' => 'required|integer|nullable',
                'status_pesanan' => 'required|string',
            ];

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $rules['harga_beli'] = 'required|numeric|min:0';
            }

            $validated = $request->validate($rules);

            $lensa = LensaKhusus::findOrFail($id);

            $lensa->sku = $validated['sku'];
            $lensa->merk = $validated['merk'];
            $lensa->desain = $validated['desain'];
            $lensa->tipe = $validated['tipe'];
            $lensa->sph = $validated['sph'];
            $lensa->cyl = $validated['cyl'];
            $lensa->add = $validated['add'] ?? null;
            $lensa->stok = $validated['stok'];
            $lensa->harga = $validated['harga'];
            $lensa->estimasi_selesai_hari = $validated['estimasi_selesai_hari'];
            $lensa->status_pesanan = $validated['status_pesanan'];
            $lensa->cabang_id = session('cabang_id');

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $lensa->harga_beli = $validated['harga_beli'];
                $lensa->laba = $validated['harga'] - $validated['harga_beli'];
            } else {
                $lensa->laba = $validated['harga'] - $lensa->harga_beli;
            }

            $lensa->save();

            return back()->with('success', 'Lensa Khusus berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi update lensa khusus gagal: ' . $e->getMessage());
            return back()->with('error', implode(' | ', $e->validator->errors()->all()))->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Lensa Khusus tidak ditemukan: ' . $e->getMessage());
            return back()->with('error', 'Lensa Khusus tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Gagal update lensa khusus: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui lensa khusus.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lensa = LensaKhusus::findOrFail($id);
            $lensa->delete();

            return back()->with('success', 'Lensa Khusus berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Lensa Khusus tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Delete lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus lensa khusus.');
        }
    }
}
