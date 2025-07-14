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
        //validasi data form
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        //hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        //simpan data registrasi ke dalam session
        $request->session()->put('registration_data', $validatedData);

        //buat dan simpan otp ke dalam session
        $otp = random_int(100000, 999999);
        $request->session()->put('otp_code', $otp);
        $request->session()->put('otp_expires_at', now()->addMinutes(10));

        //kirim otp ke email
        try{
            Mail::to($validatedData['email'])->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email OTP ke: ' . $validatedData['email'] . '. Error: ' . $e->getMessage());
            // Jika gagal, kembali dengan pesan error
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi.']);
        }

        //direct ke halaman veirifkasi OTP
        return redirect()->route('verification.notice');
    }
}