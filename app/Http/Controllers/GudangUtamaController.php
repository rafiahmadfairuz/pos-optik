<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GudangUtamaController extends Controller
{
    public function transferBarangKeCabang()
    {
        return view('TransferBarang.transferBarangKeCabang');
    }
    public function beliBarang()
    {
        return view('TransferBarang.beliBarang');
    }
}
