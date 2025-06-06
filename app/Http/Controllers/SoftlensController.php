<?php

namespace App\Http\Controllers;

use App\Models\Softlen;
use App\Models\Softlens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoftlensController extends Controller
{
    public function showSoftlens()
    {
        $softlens = Softlen::all();
        return view("Inventory.softlens", compact("softlens"));
    }

    public function createSoftlens(Request $request)
    {
        try {
            $validated = $request->validate([
                'merk' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'warna' => 'required|string|max:50',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'cabang_id' => 'nullable|exists:cabangs,id',
            ]);

            Softlen::create($validated);

            return redirect()->route('softlens')->with('success', 'Softlens berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create softlens failed: ' . $e->getMessage());

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create softlens failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan softlens.' . $e->getMessage())->withInput();
        }
    }

    public function editSoftlens(Request $request, $id)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'warna' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'cabang_id' => 'nullable|exists:cabangs,id',
        ]);

        try {
            $softlens = Softlen::findOrFail($id);

            $softlens->merk = $validated['merk'];
            $softlens->tipe = $validated['tipe'];
            $softlens->warna = $validated['warna'];
            $softlens->harga = $validated['harga'];
            $softlens->stok = $validated['stok'];
            $softlens->cabang_id = $validated['cabang_id'] ?? null;

            $softlens->save();

            return redirect()->back()->with('success', 'Softlens berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Softlens tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui softlens.');
        }
    }

    public function deleteSoftlens($id)
    {
        try {
            $softlens = Softlen::findOrFail($id);
            $softlens->delete();

            return redirect()->back()->with('success', 'Softlens berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Softlens tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus softlens.');
        }
    }
}
