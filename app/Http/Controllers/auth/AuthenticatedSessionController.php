<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan otentikasi.
     */
    public function store(Request $request)
    {
        // 1. Validasi input: 'login' bisa berisi email atau nomor telepon
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Tentukan tipe input (email atau nomor telepon)
        $loginInput = $request->input('login');
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        // 3. Normalisasi nomor telepon jika inputnya adalah nomor
        $loginValue = $loginInput;
        if ($fieldType === 'phone_number') {
            // Jika nomor diawali '0', ganti dengan format internasional '+62'
            if (str_starts_with($loginInput, '0')) {
                $loginValue = '+62' . substr($loginInput, 1);
            }
        }

        // 4. Siapkan kredensial untuk percobaan login
        $credentials = [
            $fieldType => $loginValue,
            'password' => $request->input('password'),
        ];

        // 5. Lakukan percobaan login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 6. Periksa apakah akun sudah diverifikasi
            if ($user->email_verified_at === null) {
                Auth::logout(); // Logout user yang belum terverifikasi
                return back()->withErrors([
                    'login' => 'Akun Anda belum diverifikasi. Silakan periksa email/WhatsApp Anda.',
                ])->onlyInput('login');
            }

            // 7. Jika SUDAH terverifikasi, lanjutkan ke dashboard
            $request->session()->regenerate();
            return redirect()->intended(route('beranda'));
        }

        // 8. Jika kredensial salah (otentikasi gagal)
        return back()->withErrors([
            'login' => 'Kombinasi email/nomor telepon dan password salah.',
        ])->onlyInput('login');
    }

    /**
     * Menghancurkan sesi otentikasi (logout).
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}