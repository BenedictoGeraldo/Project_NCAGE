<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller{
    //menampilkan halaman login
    public function create(){
        return view('auth.login');
    }

    //memproses login
    public function store(Request $request)
    {
    // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Lakukan percobaan login
        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();

            // 3. Periksa apakah akun sudah diverifikasi
            if ($user->email_verified_at === null) {
                Auth::logout();
            } else {
                // 4. Jika SUDAH, baru lanjutkan ke dashboard
                $request->session()->regenerate();
                return redirect()->intended(route('beranda'));
            }
        }

        // 5. Jika otentikasi gagal ATAU jika akun belum terverifikasi,
        return back()->withErrors([
            'email' => 'Email atau password yang anda masukan salah.',
        ])->onlyInput('email');
    }

    //logout function
    public function destroy(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}