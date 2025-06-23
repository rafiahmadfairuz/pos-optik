<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Softlen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SoftlensController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'gudang_utama') {
            $softlens = Softlen::whereNull('cabang_id')->get();
        } else {
            $softlens = Softlen::where('cabang_id', session('cabang_id'))->get();
        }

        return view("Inventory.softlens", compact("softlens"));
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
                'merk' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'warna' => 'required|string|max:50',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            // Hitung laba otomatis
            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            Softlen::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return redirect()->route('softlens.index')->with('success', 'Softlens berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create softlens failed: ' . $e->getMessage());

            return back()->with('error', 'Validasi gagal.')->withInput();
        } catch (\Exception $e) {
            Log::error('Create softlens failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal menambahkan softlens. ' . $e->getMessage())->withInput();
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
        $rules = [
            'merk' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'warna' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ];

        if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
            $rules['harga_beli'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        try {
            $softlens = Softlen::findOrFail($id);

            $softlens->merk = $validated['merk'];
            $softlens->tipe = $validated['tipe'];
            $softlens->warna = $validated['warna'];
            $softlens->harga = $validated['harga'];
            $softlens->stok = $validated['stok'];
            $softlens->cabang_id = session('cabang_id');

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $softlens->harga_beli = $validated['harga_beli'];
                $softlens->laba = $validated['harga'] - $validated['harga_beli'];
            } else {
                // Gunakan harga_beli dari database jika tidak boleh mengedit
                $softlens->laba = $validated['harga'] - $softlens->harga_beli;
            }

            $softlens->save();

            return back()->with('success', 'Softlens berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update softlens failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Softlens tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Update softlens failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui softlens.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $softlens = Softlen::findOrFail($id);
            $softlens->delete();

            return back()->with('success', 'Softlens berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete softlens failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Softlens tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Delete softlens failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus softlens.');
        }
    }
}
