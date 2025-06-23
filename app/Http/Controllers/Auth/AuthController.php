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
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('pilihCabang');

                case 'gudang_cabang':
                    session(['cabang_id' => $user->cabang_id]);
                    return redirect()->route('dashboard');

                case 'cabang':
                    session(['cabang_id' => $user->cabang_id]);
                    return redirect()->route('dashboard');

                case 'gudang_utama':
                    return redirect()->route('transfer.barang');

                default:
                    Auth::logout();
                    return back()->withErrors([
                        "email" => "Role pengguna tidak dikenali"
                    ]);
            }
        }

        return back()->withErrors([
            "email" => "Email atau password salah"
        ])->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
