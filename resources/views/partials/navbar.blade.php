<nav class="navbar px-4 py-3 position-relative align-items-center">
    <div class="logo fs-4 position-relative z-1 d-flex">
        <div class="img">
            <img class="w-75" src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <div class="logo-text">
            <h5 class="nav-text fw-bold mb-0 ms-auto">Pelayanan NCAGE</h5>
            <p class="nav-text fs-6 mb-0 ms-auto">Pusat Kodifikasi</p>
        </div>
    </div>
    <div class="position-absolute top-50 start-50 translate-middle">
        <ul class="d-flex list-unstyled gap-4 mb-0">
            <li><a class="btn fw-bold fs-7 {{ Request::is('/') ? 'text-white bg-active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
            <li><a class="btn rounded-4 fw-bold fs-7 {{ Request::is('pendaftaran-ncage*') ? 'text-white bg-active' : '' }}" href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}">Pendaftaran NCAGE</a></li>
            @php
                $userApplication = Auth::user()->ncageApplication ?? null;
            @endphp
            <li>
                <a class="btn fw-bold fs-7 {{ Request::is('pantau-status*') ? 'text-white bg-active' : '' }}"
                href="{{ route('tracking.index') }}">
                    Pantau Status
                </a>
            </li>
        </ul>
    </div>
    @guest
    <div class="ms-auto text-white position-relative z-1">
        <div class="btn d-flex gap-1">
            <a class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2" href="{{ route('login') }}">Masuk</a>
            <a class="btn bg-active text-white rounded-pill px-4 py-2" href="{{ route('register') }}">Daftar</a>
        </div>
    </div>
    @endguest

    @auth
        <div class="ms-auto text-white position-relative z-1 dropdown">
            {{-- TAMPILKAN INI HANYA JIKA PENGGUNA SUDAH LOGIN --}}
            <div class="profile-image">
                <i class="fa-solid fa-circle-user"></i>
            </div>

            {{-- Tombol Logout harus menggunakan form dengan method POST untuk keamanan --}}
            <div class="dropdown-menu">
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Logout
                </a>
            </form>
            </div>
        </div>
    @endauth
</nav>
