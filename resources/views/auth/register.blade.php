<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
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
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Masukkan Ulang Password" required>
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

    
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>