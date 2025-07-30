<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- META TAG UNTUK USER ID (DITAMBAHKAN) --}}
    @auth
        <meta name="user-id" content="{{ Auth::user()->id }}">
    @endauth

    <title>
        @yield('title', 'Pelayanan NCAGE')
    </title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')

    <!-- Memuat js dan css dari vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div>
        @php
            $hasPendingNcage = Auth::check() && Auth::user()->ncageApplication()
                ->whereIn('status_id', [1, 2, 3, 4]) // contoh status 'dalam proses'
                ->exists();
        @endphp
        @include('partials.navbar')
        @include('partials.offcanvas-menu')
        <main>
            @yield('content')

            {{-- pop up jika user sudah daftar --}}
            <div class="modal fade" id="sudahDaftarModal" tabindex="-1" aria-labelledby="sudahDaftarModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4 rounded-4 shadow-sm border-0 text-center">
                        <h5 class="fw-bold fs-4 mb-2" id="sudahDaftarModalLabel">Anda Sudah Memiliki Permohonan</h5>
                        <div class="border-top border-3 w-100 mx-auto mb-3"></div>
                        <div class="my-3">
                            <img src="{{ asset('images/icons/icon-info.png') }}" alt="Icon Keluar" style="height: 80px;">
                        </div>
                        <p class="text-muted mb-4">Perusahaan Anda telah mengajukan permohonan NCAGE. Untuk melihat progress pengajuan Anda, silahkan kunjungi halaman "Pantau Status".</p>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-dark-red border-2 rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                            </button>
                            <a href="{{ route('tracking.index') }}" class="btn btn-dark-red text-white rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2">
                                Pantau Status <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div>
        @include('partials.footer')
    </div>

    {{-- Script JavaScript --}}
    {{-- jQuery HARUS DIMUAT PERTAMA, sebelum script lain yang menggunakannya --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> {{-- <--- BARIS INI DITAMBAHKAN --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    {{-- Ini tempat di mana script dari @push('scripts') akan dimuat --}}
    {{-- Pastikan @stack('scripts') ADA dan berada SETELAH jQuery dan Bootstrap JS --}}
    @stack('scripts')
</body>
</html>
