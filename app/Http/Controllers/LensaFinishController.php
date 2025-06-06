<?php

namespace App\Http\Controllers;

use App\Models\LensaFinish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LensaFinishController extends Controller
{
    public function showLensaFinish()
    {
        $lensaFinish = LensaFinish::all();
        return view('Inventory.lensaFinish', compact('lensaFinish'));
    }

    public function createLensaFinish(Request $request)
    {
        try {
            $validated = $request->validate([
                'merk' => 'required|string|max:50',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'stok' => 'required|integer|min:0',
                'harga' => 'required|numeric|min:0',
                'cabang_id' => 'nullable|exists:cabangs,id',

            ]);

            LensaFinish::create($validated);

            return redirect()->route('lensaFinish')->with('success', 'Lensa berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create lensa failed: ' . $e->getMessage());

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create lensa failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal menambahkan lensa. ' . $e->getMessage())->withInput();
        }
    }

    public function editLensaFinish(Request $request, $id)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:50',
            'desain' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'sph' => 'required|numeric',
            'cyl' => 'required|numeric',
            'add' => 'nullable|numeric',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'cabang_id' => 'nullable|exists:cabangs,id',

        ]);

        try {
            $lensa = LensaFinish::findOrFail($id);

            $lensa->merk = $validated['merk'];
            $lensa->desain = $validated['desain'];
            $lensa->tipe = $validated['tipe'];
            $lensa->sph = $validated['sph'];
            $lensa->cyl = $validated['cyl'];
            $lensa->add = $validated['add'] ?? null;
            $lensa->stok = $validated['stok'];
            $lensa->harga = $validated['harga'];
            $lensa->cabang_id = $validated['cabang_id'] ?? null;

            $lensa->save();

            return redirect()->back()->with('success', 'Lensa berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Lensa tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui lensa.');
        }
    }

    public function deleteLensaFinish($id)
    {
        try {
            $lensa = LensaFinish::findOrFail($id);
            $lensa->delete();

            return redirect()->back()->with('success', 'Lensa berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Lensa tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus lensa.');
        }
    }
}
