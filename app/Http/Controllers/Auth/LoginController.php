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
        $businessUnits = MBusinessUnit::all();
        $plants = MPlant::all();

        return view('auth.login', [
            'businessUnits' => $businessUnits,
            'plants' => $plants,
        ]);
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

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            // Ambil data business unit
            $bu = MBusinessUnit::where('bu_code', $request->business_unit)->first();

            // Ambil data plant
            $pl = MPlant::where('plant_code', $request->plant)->first();

            // Simpan ke session
            session()->put([
                'business_unit_code' => $request->business_unit,
                'business_unit_name' => $bu->bu_name ?? '-',
                'business_unit_region' => $bu->region ?? '-',
                'plant_code' => $request->plant,
                'plant_name' => $pl->plant_name ?? '-',
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil! Selamat datang.');
        }

        return back()->with('error', 'Username atau Password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush(); // hapus semua session
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
