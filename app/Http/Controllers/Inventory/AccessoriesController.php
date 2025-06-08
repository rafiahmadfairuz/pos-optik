<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Accessories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AccessoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accessories = Accessories::where('cabang_id', session('cabang_id'))->get();
        return view("Inventory.accesories", compact("accessories"));
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
                'nama' => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

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
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        try {
            $accessory = Accessories::findOrFail($id);

            $accessory->nama = $validated['nama'];
            $accessory->jenis = $validated['jenis'];
            $accessory->harga = $validated['harga'];
            $accessory->stok = $validated['stok'];
            $accessory->cabang_id = session('cabang_id');


            $accessory->save();

            return back()->with('success', 'Aksesori berhasil diperbarui!');
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
