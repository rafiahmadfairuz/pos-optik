<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        return view("Informasi.kasir");
    }
}
