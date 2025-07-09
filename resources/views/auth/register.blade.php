<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Pelayanan NCAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #FDF9F3;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        .header-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header-logo img {
            max-width: 80px;
        }
        .header-logo h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #8C1515;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
        }
        .header-logo p {
            color: #333;
        }
        .card-container {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 800px;
            padding: 2rem;
        }
        .card-title {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }
        .btn-primary {
            background-color: #8C1515;
            border-color: #8C1515;
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #7a1212;
            border-color: #7a1212;
        }
        .text-center a {
            color: #8C1515;
            font-weight: 600;
            text-decoration: none;
        }
        .form-check-label {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kemhan">
        <h1>Pelayanan NCAGE</h1>
        <p>Kementerian Pertahanan Republik Indonesia</p>
    </div>

    <div class="card-container">
        <h2 class="card-title">Daftar</h2>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>