<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\Asuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asuransi = Asuransi::all();
        return view('Informasi.asuransi', compact('asuransi'));
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
                'nominal' => 'required|numeric|min:0',
            ]);

            Asuransi::create($validated);

            return redirect()->route('asuransi.index')->with('success', 'Data asuransi berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi tambah asuransi gagal: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal. Mohon periksa kembali input Anda.')->withInput();
        } catch (\Exception $e) {
            Log::error('Create asuransi failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan data asuransi.')->withInput();
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
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'nominal' => 'required|numeric|min:0',
            ]);

            $asuransi = Asuransi::findOrFail($id);
            $asuransi->update($validated);

            return back()->with('success', 'Data asuransi berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi update asuransi gagal: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal saat memperbarui data.')->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update asuransi gagal - Data tidak ditemukan: ' . $e->getMessage());
            return back()->with('error', 'Data asuransi tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Update asuransi gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data asuransi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $asuransi = Asuransi::findOrFail($id);
            $asuransi->delete();

            return back()->with('success', 'Data asuransi berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Hapus asuransi gagal - Data tidak ditemukan: ' . $e->getMessage());
            return back()->with('error', 'Data asuransi tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Hapus asuransi gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data asuransi.');
        }
    }
}
