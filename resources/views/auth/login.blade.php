<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Pelayanan NCAGE</title>
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
            max-width: 450px;
            padding: 2.5rem;
        }
        .card-title {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block; /* Selalu tampilkan pesan error jika ada */
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
        .forgot-password {
            text-align: right;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .forgot-password a {
            color: #555;
            text-decoration: none;
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
        <h2 class="card-title">Masuk</h2>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <div class="forgot-password">
                <a href="#">Lupa Password?</a>
            </div>

            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>

        <p class="text-center mt-3">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>