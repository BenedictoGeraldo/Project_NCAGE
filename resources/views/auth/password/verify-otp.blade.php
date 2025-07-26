<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode OTP</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>
<body>
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kemhan">
        <h1>Verifikasi Akun</h1>
    </div>

    <div class="card-container" style="max-width: 500px;">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <p class="text-center text-muted mb-4">
            Kami telah mengirimkan kode verifikasi 6 digit. Silakan masukkan kode di bawah ini.
        </p>

        @error('otp')
            <div class="alert alert-danger text-center">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('password.otp.verify') }}">
            @csrf
            <div class="mb-3">
                <label for="otp" class="form-label">Kode OTP</label>
                <input type="text" class="form-control" id="otp" name="otp" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary w-100">Verifikasi Kode</button>
        </form>

        <p class="text-center mt-3">
            <a href="{{ route('password.request') }}">Kirim ulang kode?</a>
        </p>
    </div>
</body>
</html>