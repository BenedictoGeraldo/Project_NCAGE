<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
</head>

<body>

    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kemhan">
        <h1>Pelayanan NCAGE</h1>
        <h5>Pusat Kodifikasi</h5>
        <hr class="mt-1">
        <p class="kemhan">Kementerian Pertahanan Republik Indonesia</p>
    </div>

    <div class="card-container">
        <div class="card-title-container">
            <a href="{{ route('home') }}" class="back-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>
            </a>
            <h2 class="card-title">Masuk</h2>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="login" class="form-label">Email atau Nomor Telepon</label>
                <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" placeholder="Masukkan Email atau Nomor Telepon" value="{{ old('login') }}" required autofocus>
                @error('login')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <svg id="eye-show" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>
                        <svg id="eye-hide" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill d-none" viewBox="0 0 16 16">
                            <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588M5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                            <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>

            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>

        <p class="text-center mt-3">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </p>
    </div>

    <script>
        function setupPasswordToggle(inputId, toggleButtonId, showIconId, hideIconId) {
            const togglePassword = document.getElementById(toggleButtonId);
            const password = document.getElementById(inputId);
            const eyeShow = document.getElementById(showIconId);
            const eyeHide = document.getElementById(hideIconId);

            if (togglePassword && password && eyeShow && eyeHide) {
                togglePassword.addEventListener('click', function (e) {
                    // ganti tipe input
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    // ganti ikon mata
                    eyeShow.classList.toggle('d-none');
                    eyeHide.classList.toggle('d-none');
                });
            }
        }

        // Panggil fungsi untuk input password di halaman login
        setupPasswordToggle('password', 'togglePassword', 'eye-show', 'eye-hide');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>