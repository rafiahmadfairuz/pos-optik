<?php

namespace App\Http\Controllers;

use App\Models\LensaKhusus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LensaKhususController extends Controller
{
    public function showLensaKhusus()
    {
        $lensaKhusus = LensaKhusus::all();
        return view('Inventory.lensaKhusus', compact('lensaKhusus'));
    }

    public function createLensaKhusus(Request $request)
    {
        try {
            $validated = $request->validate([
                'merk' => 'required|string|max:50',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'estimasi_selesai_hari' => 'required|integer',
                'status_pesanan' => 'required',
                'cabang_id' => 'nullable|exists:cabangs,id',
            ]);

            LensaKhusus::create($validated);

            return redirect()->route('lensaKhusus')->with('success', 'Lensa Khusus berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create lensa khusus failed: ' . $e->getMessage());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan lensa khusus. ' . $e->getMessage())->withInput();
        }
    }

    public function editLensaKhusus(Request $request, $id)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:50',
            'desain' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'sph' => 'required|numeric',
            'cyl' => 'required|numeric',
            'add' => 'nullable|numeric',
            'estimasi_selesai_hari' => 'required|integer',
            'status_pesanan' => 'required',
            'cabang_id' => 'nullable|exists:cabangs,id',
        ]);

        try {
            $lensa = LensaKhusus::findOrFail($id);

            $lensa->merk = $validated['merk'];
            $lensa->desain = $validated['desain'];
            $lensa->tipe = $validated['tipe'];
            $lensa->sph = $validated['sph'];
            $lensa->cyl = $validated['cyl'];
            $lensa->add = $validated['add'] ?? null;
            $lensa->estimasi_selesai_hari = $validated['estimasi_selesai_hari'];
            $lensa->status_pesanan = $validated['status_pesanan'];
            $lensa->cabang_id = $validated['cabang_id'] ?? null;

            $lensa->save();

            return redirect()->back()->with('success', 'Lensa Khusus berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Lensa Khusus tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui lensa khusus.');
        }
    }

    public function deleteLensaKhusus($id)
    {
        try {
            $lensa = LensaKhusus::findOrFail($id);
            $lensa->delete();

            return redirect()->back()->with('success', 'Lensa Khusus berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Lensa Khusus tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus lensa khusus.');
        }
    }
}
