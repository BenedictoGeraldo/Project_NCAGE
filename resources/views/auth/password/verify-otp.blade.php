<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode OTP</title>
    {{-- Sesuaikan path CSS dengan proyek Anda --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Masukkan Kode Verifikasi</div>
                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @error('otp')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <p class="mb-3">Kami telah mengirimkan kode verifikasi 6 digit. Silakan masukkan kode di bawah ini.</p>

                        <form method="POST" action="{{ route('password.otp.verify') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="otp" class="form-label">Kode OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>