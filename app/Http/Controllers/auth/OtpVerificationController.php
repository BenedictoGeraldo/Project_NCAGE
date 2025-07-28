<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class OtpVerificationController extends Controller
{
    public function show(Request $request)
    {
        // Jika tidak ada data registrasi di session, kembalikan ke halaman registrasi
        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        // DIUBAH: Validasi field 'otp' bukan 'otp_code'
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        // Ambil data dari session
        $registrationData = $request->session()->get('registration_data');
        $otp_code = $request->session()->get('otp_code');
        $otp_expires_at = $request->session()->get('otp_expires_at');

        // Jika session tidak ada, atau OTP salah, atau kedaluwarsa
        // DIUBAH: Bandingkan dengan $request->otp
        if (!$registrationData || $otp_code != $request->otp || now()->isAfter($otp_expires_at)) {
            // DIUBAH: Tampilkan error untuk field 'otp'
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // Buat instance User baru
        $user = new User();
        
        // Isi semua data dari session
        $user->fill($registrationData);
        
        // Tetapkan waktu verifikasi secara eksplisit
        $user->email_verified_at = now();
        
        // Baru simpan user ke database
        $user->save();
        
        // Hapus semua data sementara dari session
        $request->session()->forget(['registration_data', 'otp_code', 'otp_expires_at']);

        // Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Verifikasi berhasil! Akun Anda telah dibuat, silakan login.');
    }
}