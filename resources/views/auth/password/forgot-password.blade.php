<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    {{-- Sesuaikan path CSS dengan proyek Anda --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>
<body>
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kemhan">
        <h1>Lupa Password</h1>
    </div>

    <div class="card-container" style="max-width: 500px;">
        <p class="text-center text-muted mb-4">
            Masukkan email atau nomor telepon Anda yang terdaftar untuk menerima kode verifikasi (OTP).
        </p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        @error('login')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('password.send.otp') }}">
            @csrf
            <div class="mb-3">
                <label for="login" class="form-label">Email atau Nomor Telepon</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim Kode Verifikasi</button>
        </form>

        <p class="text-center mt-3">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>