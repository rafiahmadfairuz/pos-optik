<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsuransiController extends Controller
{
    public function showAsuransi()
    {
        $asuransi = Asuransi::all();
        return view('Informasi.asuransi', compact('asuransi'));
    }

    public function createAsuransi(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'nominal' => 'required|numeric|min:0',
            ]);

            Asuransi::create($validated);

            return redirect()->route('asuransi')->with('success', 'Data asuransi berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create asuransi failed: ' . $e->getMessage());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create asuransi failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan data asuransi.')->withInput();
        }
    }

    public function editAsuransi(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:0',
        ]);

        try {
            $asuransi = Asuransi::findOrFail($id);

            $asuransi->nama = $validated['nama'];
            $asuransi->nominal = $validated['nominal'];

            $asuransi->save();

            return redirect()->back()->with('success', 'Data asuransi berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Data asuransi tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui data asuransi.');
        }
    }

    public function deleteAsuransi($id)
    {
        try {
            $asuransi = Asuransi::findOrFail($id);
            $asuransi->delete();

            return redirect()->back()->with('success', 'Data asuransi berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Data asuransi tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus data asuransi.');
        }
    }
}
