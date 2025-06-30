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
        if ($id === 'gudang_utama') {
            session()->forget('cabang_id');
            return redirect()->route('transfer.barang')
                ->with('success', 'Masuk sebagai Gudang Utama');
        }

        $cabang = Cabang::find($id);

        if (!$cabang) {
            return redirect()->back()->with('error', 'Cabang tidak ditemukan.');
        }

        session(['cabang_id' => $cabang->id]);
        return redirect()->route('dashboard')
            ->with('success', 'Masuk ke cabang ' . $cabang->nama);
    }
}
