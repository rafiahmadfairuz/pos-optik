<?php

namespace App\Http\Controllers\Navigation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $cabangId = session('cabang_id');

        $leastStockProducts = DB::select("
        SELECT id, merk AS nama_produk, stok, 'frame' AS kategori FROM frames WHERE cabang_id = ?
        UNION ALL
        SELECT id, merk AS nama_produk, stok, 'lensa_finish' AS kategori FROM lensa_finishes WHERE cabang_id = ?
        UNION ALL
        SELECT id, nama AS nama_produk, stok, 'accessories' AS kategori FROM accessories WHERE cabang_id = ?
        UNION ALL
        SELECT id, merk AS nama_produk, stok, 'softlens' AS kategori FROM softlens WHERE cabang_id = ?
        ORDER BY stok ASC
        LIMIT 20
    ", [$cabangId, $cabangId, $cabangId, $cabangId]);

        return view('Dashboard.dashboard', compact('leastStockProducts'));
    }
}
