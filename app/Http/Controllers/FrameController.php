<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FrameController extends Controller
{
    public function showFrame()
    {
        $frame = Frame::all();
        return view('Inventory.frame', compact('frame'));
    }

    public function createFrame(Request $request)
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

            Frame::create($validated);

            return redirect()->route('frame')->with('success', 'Frame berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create frame failed: ' . $e->getMessage());

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create frame failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal menambahkan frame. ' . $e->getMessage())->withInput();
        }
    }

    public function editFrame(Request $request, $id)
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
            $frame = Frame::findOrFail($id);

            $frame->merk = $validated['merk'];
            $frame->tipe = $validated['tipe'];
            $frame->warna = $validated['warna'];
            $frame->harga = $validated['harga'];
            $frame->stok = $validated['stok'];
            $frame->cabang_id = $validated['cabang_id'] ?? null;

            $frame->save();

            return redirect()->back()->with('success', 'Frame berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Frame tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal memperbarui frame.');
        }
    }

    public function deleteFrame($id)
    {
        try {
            $frame = Frame::findOrFail($id);
            $frame->delete();

            return redirect()->back()->with('success', 'Frame berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Frame tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus frame.');
        }
    }
}
