<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/registration.css') }}">
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

    <div class="card-container mb-3">
        <div class="card-title-container">
            <a href="{{ route('home') }}" class="back-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </a>
            <h2 class="card-title">Daftar</h2>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap (Point of Contact)</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Narahubung" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Masukkan Nama Perusahaan" value="{{ old('company_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon (Whatsapp)</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Masukkan Nomor Telepon" value="{{ old('phone_number') }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}" required>
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
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Masukkan Ulang Password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                <svg id="eye-show-confirm" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                                <svg id="eye-hide-confirm" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill d-none" viewBox="0 0 16 16">
                                    <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588M5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                                    <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Opsi kirim Kode verifikasi-->
            <div class="mb-3">
                <label for="otp_delivery_method" class="form-label">Metode Pengiriman Kode Verifikasi</label>
                <select class="form-select" id="otp_delivery_method" name="otp_delivery_method" required>
                    <option value="email" {{ old('otp_delivery_method') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="whatsapp" {{ old('otp_delivery_method') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>
            <!--Akhir opsi kirim Kode verifikasi-->
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    Saya telah membaca dan setuju dengan Syarat & Ketentuan serta Kebijakan Privasi.
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>

        <p class="text-center mt-3">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk Sekarang</a>
        </p>
    </div>

    <!--ini script untuk ubah format no.telp-->
    <script>
        const phoneInput = document.getElementById('phone_number');

        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Cek jika nilai input dimulai dengan "08"
                if (this.value.startsWith('08')) {
                    // Ganti "08" dengan "+628" dan tambahkan sisa nomornya
                    this.value = '+628' + this.value.substring(2);
                }
            });
        }
    </script>

    <!--ini script untuk toggle password visibility-->
    <script>
        function setupPasswordToggle(inputId, toggleButtonId, showIconId, hideIconId) {
            const togglePassword = document.getElementById(toggleButtonId);
            const password = document.getElementById(inputId);
            const eyeShow = document.getElementById(showIconId);
            const eyeHide = document.getElementById(hideIconId);

            if (togglePassword && password && eyeShow && eyeHide) { // Ditambahkan pengecekan ikon
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

        // Panggil fungsi untuk input password utama
        setupPasswordToggle('password', 'togglePassword', 'eye-show', 'eye-hide');

        // Panggil fungsi untuk input konfirmasi password dengan ID unik
        setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation', 'eye-show-confirm', 'eye-hide-confirm');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    
</body>
</html>
