<?php

namespace App\Http\Controllers\Inventory;

use App\Models\LensaFinish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LensaFinishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'gudang_utama') {
            $lensaFinish = LensaFinish::whereNull('cabang_id')->get();
        } else {
            $lensaFinish = LensaFinish::where('cabang_id', session('cabang_id'))->get();
        }

        return view('Inventory.lensaFinish', compact('lensaFinish'));
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
                'stok' => 'required|integer|min:0',
                'harga_beli' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
            ]);

            // Hitung laba otomatis
            $validated['laba'] = $validated['harga'] - $validated['harga_beli'];

            LensaFinish::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return redirect()->route('lensaFinish.index')->with('success', 'Lensa berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create lensa failed: ' . $e->getMessage());

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create lensa failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal menambahkan lensa. ' . $e->getMessage())->withInput();
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
            'desain' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'sph' => 'required|numeric',
            'cyl' => 'required|numeric',
            'add' => 'nullable|numeric',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
        ];

        if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
            $rules['harga_beli'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

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
            $lensa->cabang_id = session('cabang_id');

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'gudang') {
                $lensa->harga_beli = $validated['harga_beli'];
                $lensa->laba = $validated['harga'] - $validated['harga_beli'];
            } else {
                // Gunakan harga_beli yang sudah ada
                $lensa->laba = $validated['harga'] - $lensa->harga_beli;
            }

            $lensa->save();

            return redirect()->back()->with('success', 'Lensa berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Lensa tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui lensa.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
