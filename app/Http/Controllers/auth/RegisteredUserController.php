<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    //menampilkan halaman registrasi
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //validasi form regis
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);


        //membuat user baru
        $user = User::create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otp = random_int(100000, 999999); //membuat kode otp acak
        $otpExpiresAt = now()->addMinutes(10); //menentukan waktu kadaluarsa kode otp selama 10 menit
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' =>$otpExpiresAt,
        ]); //menyimpan otp dan waktu kadaluarsanya ke database user

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            //log bila email gagal terkiirm
            Log::error('Gagal mengirim email OTP ke: ' . $user->email . '. Error: ' . $e->getMessage());
        }

        return redirect()->route('verification.notice')->with('email', $user->email);
    }
}