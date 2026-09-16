<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pilihCabang()
    {
        $cabangs = Cabang::all();
        return view('Admin.admin', compact("cabangs"));
    }
    public function setCabang($id, Request $request)
    {
        // Cari cabang berdasarkan ID (bisa angka 0 untuk pusat, atau 1,2,3,4 untuk cabang)
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return redirect()->back()->with('error', 'Cabang tidak ditemukan.');
        }

        // Simpan ID-nya langsung ke session (apakah itu 0 atau lainnya)
        session(['cabang_id' => $cabang->id]);

        // Jika yang dipilih Gudang Pusat (ID 0), arahkan ke transfer/pusat. Jika cabang lain ke dashboard.
        if ($cabang->id == 0) {
            return redirect()->route('transfer.barang')
                ->with('success', 'Masuk sebagai ' . $cabang->nama);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Masuk ke ' . $cabang->nama);
    }
}
