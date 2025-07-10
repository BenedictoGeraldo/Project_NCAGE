<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    //memproses reset password
    public function reset(Request $request)
    {
        //1. Validasi semua input dari form
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //2. Coba reset password menggunakan sistem bawaan laravel
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password){
            //3. jika token dan email valid, update password pengguna
            $user->forceFill([
                'password'=> Hash::make($password)
            ])->save();
        });

        //4. Periksa status dan arahkan pengguna
        if ($status == Password::PASSWORD_RESET){
            return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset');
        }

        //jika token tidak valid atau email salah
        return back()->withErrors(['email' => 'Token reset password tidak valid']);
    }
}
