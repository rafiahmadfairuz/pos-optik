<?php

namespace App\Http\Controllers\Inventory;

use App\Models\LensaKhusus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LensaKhususController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lensaKhusus = LensaKhusus::where('cabang_id', session('cabang_id'))->get();
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
                'merk' => 'required|string|max:50',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'estimasi_selesai_hari' => 'required|integer',
                'status_pesanan' => 'required',
            ]);

            LensaKhusus::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return redirect()->route('lensaKhusus.index')->with('success', 'Lensa Khusus berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create lensa khusus failed: ' . $e->getMessage());
            // Tangani validasi error pakai session 'error' supaya bisa trigger modal kamu
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
            $validated = $request->validate([
                'merk' => 'required|string|max:50',
                'desain' => 'required|string|max:50',
                'tipe' => 'required|string|max:50',
                'sph' => 'required|numeric',
                'cyl' => 'required|numeric',
                'add' => 'nullable|numeric',
                'estimasi_selesai_hari' => 'required|integer',
                'status_pesanan' => 'required',

            ]);

            $lensa = LensaKhusus::findOrFail($id);

            $lensa->merk = $validated['merk'];
            $lensa->desain = $validated['desain'];
            $lensa->tipe = $validated['tipe'];
            $lensa->sph = $validated['sph'];
            $lensa->cyl = $validated['cyl'];
            $lensa->add = $validated['add'] ?? null;
            $lensa->estimasi_selesai_hari = $validated['estimasi_selesai_hari'];
            $lensa->status_pesanan = $validated['status_pesanan'];
            $lensa->cabang_id = session('cabang_id');

            $lensa->save();

            return back()->with('success', 'Lensa Khusus berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', implode(' | ', $e->validator->errors()->all()))->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Lensa Khusus tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Update lensa khusus failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui lensa khusus.');
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
