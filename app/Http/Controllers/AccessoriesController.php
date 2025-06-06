<?php

namespace App\Http\Controllers;

use App\Models\Accessories;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccessoriesController extends Controller
{
    public function showAccessories()
    {
        $accessories = Accessories::all();
        return view("Inventory.accesories", compact("accessories"));
    }

    public function createAccessories(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'jenis' => 'required|string|max:50',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'cabang_id' => 'nullable|exists:cabangs,id',
            ]);

            Accessories::create($validated);

            return redirect()->route('accessories')->with('success', 'Aksesori berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create accessory failed: ' . $e->getMessage());

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create accessory failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan aksesori.')->withInput();
        }
    }

    public function editAccessories(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'cabang_id' => 'nullable|exists:cabangs,id',
        ]);

        try {
            $accessory = Accessories::findOrFail($id);

            $accessory->nama = $validated['nama'];
            $accessory->jenis = $validated['jenis'];
            $accessory->harga = $validated['harga'];
            $accessory->stok = $validated['stok'];
            $accessory->cabang_id = $validated['cabang_id'] ?? null;

            $accessory->save();

            return redirect()->back()->with('success', 'Aksesori berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Aksesori tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui aksesori.');
        }
    }

    public function deleteAccessories($id)
    {
        try {
            $accessory = Accessories::findOrFail($id);
            $accessory->delete();

            return redirect()->back()->with('success', 'Aksesori berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Aksesori tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus aksesori.');
        }
    }
}
