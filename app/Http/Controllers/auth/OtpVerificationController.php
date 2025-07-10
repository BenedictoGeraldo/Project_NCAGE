<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // <-- Tambahkan ini
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class OtpVerificationController extends Controller
{
    /**
     * Menampilkan halaman formulir verifikasi OTP.
     */
    public function show(Request $request)
    {
        if (!$request->session()->has('email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric|digits:6',
        ]);

        // 2. Cari pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan, atau OTP salah, atau OTP sudah kedaluwarsa
        if (!$user || $user->otp_code !== $request->otp_code || now()->isAfter($user->otp_expires_at)) {
            // Kembalikan dengan pesan error
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        // 3. Jika OTP valid, update status verifikasi
        $user->update([
            'email_verified_at' => now(),
            'otp_code' => null, // Hapus OTP setelah berhasil
            'otp_expires_at' => null, // Hapus waktu kedaluwarsa
        ]);

        // 4. Arahkan ke halaman login
        return redirect()->route('login')->with('status', 'Verifikasi berhasil! Selamat datang.');
    }
}