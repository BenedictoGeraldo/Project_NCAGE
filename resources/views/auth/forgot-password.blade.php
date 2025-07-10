<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    {{-- Kita bisa pakai CSS yang sama dengan halaman login --}}
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
            Masukkan alamat email Anda, dan kami akan mengirimkan link untuk mereset password Anda.
        </p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim Link Reset Password</button>
        </form>

        <p class="text-center mt-3">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>