<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>
<body>
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kemhan">
        <h1>Buat Password Baru</h1>
    </div>

    <div class="card-container" style="max-width: 500px;">
        <form method="POST" action="{{ route('password.update') }}"> {{-- Rute ini akan kita buat di Tahap 5 --}}
            @csrf
            
            {{-- Input tersembunyi untuk token dan email --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autofocus>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
</body>
</html>