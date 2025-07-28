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
use Twilio\Rest\Client; // BARU: Import library Twilio

class RegisteredUserController extends Controller
{
    //menampilkan halaman registrasi
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // MODIFIKASI: Tambahkan validasi untuk 'otp_delivery_method'
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            'otp_delivery_method' => ['required', 'in:email,whatsapp'], // BARU: Validasi pilihan OTP
        ]);

        //hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        //simpan data registrasi ke dalam session
        $request->session()->put('registration_data', $validatedData);

        //buat dan simpan otp ke dalam session
        $otp = random_int(100000, 999999);
        $request->session()->put('otp_code', $otp);
        $request->session()->put('otp_expires_at', now()->addMinutes(5)); // OTP berlaku 5 menit

        // BARU: Percabangan logika untuk mengirim OTP
        if ($validatedData['otp_delivery_method'] === 'whatsapp') {
            try {
                $this->sendWhatsAppOtp($validatedData['phone_number'], $otp);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim WhatsApp OTP ke: ' . $validatedData['phone_number'] . '. Error: ' . $e->getMessage());
                return back()->withErrors(['phone_number' => 'Gagal mengirim OTP WhatsApp. Pastikan nomor benar dan coba lagi.']);
            }
        } else {
            // Logika pengiriman email yang sudah ada
            try {
                Mail::to($validatedData['email'])->send(new SendOtpMail($otp));
            } catch (\Exception $e) {
                // dd($e); //untuk tes error
                Log::error('Gagal mengirim email OTP ke: ' . $validatedData['email'] . '. Error: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi.']);
            }
        }

        //direct ke halaman verifikasi OTP
        return redirect()->route('verification.notice');
    }

    /**
     * BARU: Method untuk mengirim pesan OTP via WhatsApp menggunakan Twilio.
     */
    private function sendWhatsAppOtp(string $recipientPhoneNumber, string $otp)
    {
        // Normalisasi nomor telepon: pastikan formatnya +62
        // Ini akan mengubah "0812..." menjadi "+62812..."
        if (str_starts_with($recipientPhoneNumber, '08')) {
            $recipientPhoneNumber = '+62' . substr($recipientPhoneNumber, 1);
        }

        $sid    = config('services.twilio.sid');
        $token  = config('services.twilio.token');
        $from   = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
             Log::error('Kredensial Twilio tidak lengkap. Cek file .env dan config/services.php');
             // Melempar exception agar bisa ditangkap di method store
             throw new \Exception('Konfigurasi Twilio tidak lengkap.');
        }

        $twilio = new Client($sid, $token);

        // Sesuaikan body pesan dengan template yang sudah disetujui di Twilio
        $message = "Kode verifikasi Anda untuk pendaftaran NCAGE adalah {$otp}.";

        $twilio->messages->create(
            "whatsapp:{$recipientPhoneNumber}",
            [
                "from" => "whatsapp:{$from}",
                "body" => $message
            ]
        );
    }
}