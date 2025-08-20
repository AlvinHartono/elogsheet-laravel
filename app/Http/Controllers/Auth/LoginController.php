<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\MBusinessUnit;
use App\Models\MPlant;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $businessUnits = MBusinessUnit::all();   // ambil semua business unit
        $plants = MPlant::all();                 // ambil semua plant

        return view('auth.login', compact('businessUnits', 'plants'));
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'business_unit' => 'required|string',
            'plant' => 'required|string',
        ]);

        // Ambil username & password dari input
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Simpan pilihan ke session
            session([
                'business_unit' => $request->business_unit,
                'plant' => $request->plant,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil! Selamat datang.');
        }

        // Login gagal
        return back()->with('error', 'Username atau Password salah.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
