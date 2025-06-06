<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view("Auth.login");
    }

    public function processLogin(Request $request)
    {
        $userData = $request->validate([
            'email' => 'required|email:rfc|min:3',
            'password' => 'required|string|min:4',
        ]);


        if (Auth::attempt($userData)) {
            $request->session()->regenerate();
            return redirect()->intended("/");
        };

        return back()->withErrors([
            "email" => "Email Atau Password Salah"
        ])->withInput();
    }


}
