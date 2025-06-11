<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\Orderan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderanController extends Controller
{
    public function index()
    {
        $cabangId = session('cabang_id');

        $orderans = Orderan::where('cabang_id', $cabangId)
            ->with('user') // jika ingin ambil data customer
            ->orderByDesc('created_at')
            ->get();

        return view('Informasi.orderan', compact('orderans'));
    }

    public function orderanDetail($id)
    {
        $order = Orderan::with([
            'items.itemable', // load morph relasinya
            'resep'
        ])->findOrFail($id);

        return view('Informasi.detailOrderan', compact('order'));
    }
}
