<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    {{-- Anda bisa menggunakan CSS yang sama dengan halaman registrasi/login --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/registration.css') }}">
</head>
<body>
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kemhan">
        <h1>Verifikasi Email Anda</h1>
    </div>

    <div class="card-container" style="max-width: 500px;">
        <h2 class="card-title d-flex justify-content-center text-center">Masukkan Kode OTP</h2>
        <p class="text-center text-muted mb-4">
            Kami telah mengirimkan kode 6 digit ke Nomor WhatsApp atau Email Anda.
        </p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('otp.verify') }}"> {{-- Rute ini akan kita buat di Tahap 5 --}}
            @csrf

            <input type="hidden" name="email" value="{{ session('registration_data')['email'] ?? '' }}">

            <div class="mb-3">
                <label for="otp_code" class="form-label">Kode OTP</label>
                <input type="text" class="form-control text-center" id="otp_code" name="otp_code"
                       maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
        </form>

        <div class="text-center mt-3">
            {{-- Tambahkan fitur kirim ulang OTP di sini nanti --}}
        </div>
    </div>
</body>
</html>