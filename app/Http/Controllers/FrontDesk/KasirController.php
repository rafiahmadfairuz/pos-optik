<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $users = User::where('cabang_id', session('cabang_id'))->get();
        return view("Informasi.kasir", compact("users"));
    }
}
