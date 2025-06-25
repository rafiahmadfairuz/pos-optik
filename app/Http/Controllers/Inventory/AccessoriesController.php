<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Accessories;
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
        $user = Auth::user();
        $search = $request->input('search');

        $query = Accessories::query();

        // Filter berdasarkan cabang
        if ($user->role === 'gudang_utama') {
            $query->whereNull('cabang_id');
        } else {
            $query->where('cabang_id', session('cabang_id'));
        }

        // Filter search berdasarkan nama atau jenis
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%");
            });
        }

        $accessories = $query->get();

        return view('Inventory.accesories', compact('accessories'));
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
                'sku' => 'required|string|max:50|unique:accessories,sku',
                'nama' => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            // Hitung laba otomatis
            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            Accessories::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return back()->with('success', 'Aksesori berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create accessory failed (validation): ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal.')->withInput();
        } catch (\Exception $e) {
            Log::error('Create accessory failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
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
                'sku' => 'required|string|max:50|unique:accessories,sku,' . $id,
                'nama' => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ];

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $rules['harga_beli'] = 'required|numeric|min:0';
            }

            $validated = $request->validate($rules);

            $accessory = Accessories::findOrFail($id);

            $accessory->sku = $validated['sku'];
            $accessory->nama = $validated['nama'];
            $accessory->jenis = $validated['jenis'];
            $accessory->harga = $validated['harga'];
            $accessory->stok = $validated['stok'];
            $accessory->cabang_id = session('cabang_id');

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $accessory->harga_beli = $validated['harga_beli'];
                $accessory->laba = $validated['harga'] - $validated['harga_beli'];
            } else {
                $accessory->laba = $validated['harga'] - $accessory->harga_beli;
            }

            $accessory->save();

            return back()->with('success', 'Aksesori berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update accessory validation failed: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal: ' . $e->getMessage())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update accessory failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Aksesori tidak ditemukan.')->withInput();
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
            $accessory = Accessories::findOrFail($id);
            $accessory->delete();

            return back()->with('success', 'Aksesori berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete accessory failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Aksesori tidak ditemukan.')->withInput();
        } catch (\Exception $e) {
            Log::error('Delete accessory failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus aksesori.')->withInput();
        }
    }
}
