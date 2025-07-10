<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    //menangani permintaan mengirim link reset password
    public function sendResetLinkEmail(Request $request)
    {
        //1. Validasi input email
        $request->validate(['email' => 'required|email']);

        //2. Kirim link reset menggunakan sistem bawaan laravel
        $status = Password::sendResetLink($request->only('email'));

        //3. Periksa status pengiriman dan memberikan respon
        if ($status == Password::RESET_LINK_SENT){
            return redirect()->route('password.request')->with('status', 'Link reset password telah dikirim ke email Anda!');
        }

        //memberi status invalid user bila email tidak ditemukan
        return redirect()->route('password.request')->withErrors(['email' => 'Tidak ada pengguna dengan alamat email tersebut']);
    }
}
