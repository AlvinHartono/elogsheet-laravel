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


    // public function login(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //         'business_unit' => 'required|string',
    //         'plant' => 'required|string',
    //     ]);

    //     $credentials = $request->only('username', 'password');

    //     if (Auth::attempt($credentials)) {

    //         // Ambil data business unit
    //         $bu = MBusinessUnit::where('bu_code', $request->business_unit)->first();

    //         // Ambil data plant
    //         $pl = MPlant::where('plant_code', $request->plant)->first();

    //         // Simpan ke session
    //         session()->put([
    //             'business_unit_code' => $bu->bu_code,
    //             'business_unit_name' => $bu->bu_name ?? '-',
    //             'plant_name' => $pl->plant_name ?? '-',
    //             'plant_code' => $pl->plant_code ?? '-',
    //         ]);

    //         return redirect()->route('dashboard')
    //             ->with('success', 'Login berhasil! Selamat datang.');
    //     }

    //     return back()->with('error', 'Username atau Password salah.');
    // }

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

        // Coba login normal (hash bcrypt)
        if (Auth::attempt($credentials)) {
            return $this->afterLoginSuccess($request);
        }

        // Fallback: cek manual plain password
        $user = \App\Models\MUser::where('username', $request->username)
            ->where('password', $request->password) // plain text check
            ->first();

        if ($user) {
            Auth::login($user);
            return $this->afterLoginSuccess($request);
        }

        return back()->with('error', 'Username atau Password salah.');
    }

    private function afterLoginSuccess(Request $request)
    {
        // Business unit & plant (sudah ada)
        $bu = \App\Models\MBusinessUnit::where('bu_code', $request->business_unit)->first();
        $pl = \App\Models\MPlant::where('plant_code', $request->plant)->first();

        session()->put([
            'business_unit_code' => $bu->bu_code ?? '-',
            'business_unit_name' => $bu->bu_name ?? '-',
            'plant_code' => $pl->plant_code ?? '-',
            'plant_name' => $pl->plant_name ?? '-',
        ]);

        // === Ambil menu sesuai role ===
        // $menus = \App\Models\MMenu::where('isactive', 'T')
        //     ->whereNull('parent_id')
        //     ->whereHas('roleMenus', function ($query) {
        //         $query->where('role_code', auth()->user()->roles);
        //     })
        //     ->get();
        $menus = \App\Models\MMenu::whereHas('roleMenus', function ($q) {
            $q->where('role_code', auth()->user()->roles);
        })
            ->where('isactive', 'T')
            ->whereNull('parent_id')
            ->with([
                'children' => function ($q) {
                    $q->where('isactive', 'T')
                        ->whereHas('roleMenus', function ($r) {
                            $r->where('role_code', auth()->user()->roles);
                        });
                }
            ])
            ->orderBy('sort_order')
            ->get();





        session()->put('menus', $menus);

        return redirect()->route('dashboard')
            ->with('success', 'Login berhasil! Selamat datang.');
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush(); // hapus semua session
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
