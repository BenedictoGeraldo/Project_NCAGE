<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller{
    //menampilkan halaman login
    public function create(){
        return view('auth.login');
    }

    //memproses login
    public function store(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('/'); //masih menunggu nama file setelah masuk login apa
        }

        //bila gagal, di direct ke login dengan pesan error
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