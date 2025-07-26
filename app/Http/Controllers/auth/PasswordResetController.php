<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetOtpMail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan form untuk meminta OTP reset password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.password.forgot-password');
    }

    /**
     * Memproses input (email/telepon), membuat OTP, dan mengirimkannya.
     */
    public function sendResetOtp(Request $request)
    {
        // 1. Validasi input
        $request->validate(['login' => 'required']);
        $loginInput = $request->input('login');

        // 2. Tentukan tipe input & cari pengguna
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $user = null;

        if ($fieldType === 'phone_number') {
            $normalizedPhone = $loginInput;
            if (str_starts_with($loginInput, '0')) {
                $normalizedPhone = '+62' . substr($loginInput, 1);
            }
            $user = User::where('phone_number', $normalizedPhone)->first();
        } else {
            $user = User::where('email', $loginInput)->first();
        }

        // 3. Jika pengguna tidak ditemukan, kembali dengan error
        if (!$user) {
            return back()->withErrors(['login' => 'Tidak ada pengguna yang terdaftar dengan email atau nomor telepon ini.']);
        }

        // 4. Generate & simpan OTP ke database
        $otp = random_int(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5); // OTP berlaku 5 menit
        $user->save();
        
        // 5. Simpan identifier (email/telepon) ke session untuk tahap verifikasi
        $request->session()->put('password_reset_identifier', $loginInput);

        // 6. Kirim OTP sesuai tipe input
        try {
            if ($fieldType === 'phone_number') {
                $this->sendWhatsAppOtp($user->phone_number, $otp);
            } else {
                Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
            }
        } catch (\Exception $e) {
            return back()->withErrors(['login' => 'Gagal mengirim kode verifikasi. Silakan coba lagi nanti.']);
        }
        
        // 7. Arahkan ke halaman form untuk memasukkan OTP
        return redirect()->route('password.otp.form')->with('status', 'Kode verifikasi telah dikirim. Silakan cek email atau WhatsApp Anda.');
    }

    /**
     * Menampilkan form untuk memasukkan OTP.
     */
    public function showOtpForm(Request $request)
    {
        // Cek apakah session untuk reset password ada, jika tidak, kembalikan
        if (!$request->session()->has('password_reset_identifier')) {
            return redirect()->route('password.request')->withErrors(['login' => 'Silakan minta ulang kode verifikasi.']);
        }

        return view('auth.password.verify-otp');
    }

    /**
     * Memverifikasi OTP yang dimasukkan oleh pengguna.
     */
    public function verifyOtp(Request $request)
    {
        // 1. Validasi input OTP
        $request->validate(['otp' => 'required|numeric|digits:6']);

        // 2. Ambil identifier (email/telepon) dari session
        $identifier = $request->session()->get('password_reset_identifier');
        if (!$identifier) {
            return redirect()->route('password.request')->withErrors(['login' => 'Sesi Anda telah habis, silakan minta ulang kode verifikasi.']);
        }

        // 3. Cari pengguna berdasarkan identifier
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $user = null;
        if ($fieldType === 'phone_number') {
            $normalizedPhone = str_starts_with($identifier, '0') ? '+62' . substr($identifier, 1) : $identifier;
            $user = User::where('phone_number', $normalizedPhone)->first();
        } else {
            $user = User::where('email', $identifier)->first();
        }

        // 4. Cek apakah OTP benar dan belum kedaluwarsa
        if ($user && $user->otp_code === $request->input('otp') && now()->isBefore($user->otp_expires_at)) {
            // Jika berhasil, tandai bahwa OTP sudah diverifikasi
            $request->session()->put('otp_verified', true);
            
            // Arahkan ke form untuk membuat password baru
            return redirect()->route('password.reset.form');
        }

        // 5. Jika gagal, kembali dengan pesan error
        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
    }

    /**
     * Method privat untuk mengirim OTP via WhatsApp.
     */
    private function sendWhatsAppOtp(string $recipientPhoneNumber, string $otp)
    {
        $sid    = config('services.twilio.sid');
        $token  = config('services.twilio.token');
        $from   = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
             throw new \Exception('Konfigurasi Twilio tidak lengkap.');
        }

        $twilio = new Client($sid, $token);
        $message = "Kode verifikasi reset password Anda adalah {$otp}. Kode ini hanya berlaku selama 5 menit.";

        $twilio->messages->create("whatsapp:{$recipientPhoneNumber}", [
            "from" => "whatsapp:{$from}",
            "body" => $message
        ]);
    }

    /**
     * Menampilkan form untuk membuat password baru.
     * Hanya bisa diakses setelah OTP diverifikasi.
     */
    public function showResetForm(Request $request)
    {
        // Pastikan pengguna sudah melalui tahap verifikasi OTP
        if (!$request->session()->get('otp_verified')) {
            return redirect()->route('password.request')->withErrors(['login' => 'Silakan verifikasi OTP terlebih dahulu.']);
        }

        return view('auth.password.reset-password');
    }

    /**
     * Menyimpan password baru ke database.
     */
    public function updatePassword(Request $request)
    {
        // 1. Pastikan pengguna sudah melalui tahap verifikasi OTP
        if (!$request->session()->get('otp_verified')) {
            return redirect()->route('password.request')->withErrors(['login' => 'Sesi Anda tidak valid.']);
        }

        // 2. Validasi password baru
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // 3. Ambil identifier dari session dan cari pengguna
        $identifier = $request->session()->get('password_reset_identifier');
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        $user = null;
        if ($fieldType === 'phone_number') {
            $normalizedPhone = str_starts_with($identifier, '0') ? '+62' . substr($identifier, 1) : $identifier;
            $user = User::where('phone_number', $normalizedPhone)->first();
        } else {
            $user = User::where('email', $identifier)->first();
        }
        
        // 4. Jika user ditemukan, update password & hapus OTP
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();
            
            // 5. Hapus semua session yang berhubungan dengan reset password
            $request->session()->forget(['password_reset_identifier', 'otp_verified']);

            // 6. Arahkan ke halaman login dengan pesan sukses
            return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset. Silakan login dengan password baru.');
        }
        
        // Fallback jika user tidak ditemukan karena suatu hal
        return redirect()->route('password.request')->withErrors(['login' => 'Terjadi kesalahan. Silakan coba lagi.']);
    }
}