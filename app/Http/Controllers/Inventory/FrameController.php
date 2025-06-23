<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Frame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'gudang_utama') {
            $frame = Frame::whereNull('cabang_id')->get();
        } else {
            $frame = Frame::where('cabang_id', session('cabang_id'))->get();
        }

        return view('Inventory.frame', compact('frame'));
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

            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            Frame::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return redirect()->route('frame.index')->with('success', 'Frame berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create frame validation failed: ' . $e->getMessage());

            return back()->with('error', 'Validasi gagal: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            Log::error('Create frame failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal menambahkan frame. ' . $e->getMessage())->withInput();
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

            $frame = Frame::findOrFail($id);

            $frame->merk = $validated['merk'];
            $frame->tipe = $validated['tipe'];
            $frame->warna = $validated['warna'];
            $frame->harga = $validated['harga'];
            $frame->stok = $validated['stok'];
            $frame->cabang_id = session('cabang_id');

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $frame->harga_beli = $validated['harga_beli'];
                $frame->laba = $validated['harga'] - $validated['harga_beli'];
            } else {
                // jika bukan admin/gudang, ambil harga_beli dari data lama
                $frame->laba = $validated['harga'] - $frame->harga_beli;
            }

            $frame->save();

            return back()->with('success', 'Frame berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update frame validation failed: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal: ' . $e->getMessage())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update frame failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Frame tidak ditemukan.')->withInput();
        } catch (\Exception $e) {
            Log::error('Update frame failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui frame.')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $frame = Frame::findOrFail($id);
            $frame->delete();

            return back()->with('success', 'Frame berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete frame failed - not found: ' . $e->getMessage());

            return back()->with('error', 'Frame tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Delete frame failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal menghapus frame.');
        }
    }
}
