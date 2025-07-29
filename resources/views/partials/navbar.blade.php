<nav class="navbar navbar-expand-lg bg-nav px-4 py-3 align-items-center">
    <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand logo fs-4 d-flex align-items-center">
            <div class="img">
                <img class="w-75" src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div class="logo-text">
                <h5 class="nav-text fw-bold mb-0">Pelayanan NCAGE</h5>
                <p class="nav-text fs-6 mb-0">Pusat Kodifikasi</p>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="position-absolute top-50 start-50 translate-middle d-none d-lg-block">
            <ul class="d-flex list-unstyled gap-4 mb-0">
                <li>
                    <a class="btn fw-bold fs-7 {{ Request::is('/', 'beranda') ? 'text-white bg-active' : '' }}" href="{{ route('home') }}">
                        Beranda
                    </a>
                </li>
                <li>
                    @if ($hasPendingNcage)
                        <a href="#" class="btn rounded-4 fw-bold fs-7"
                        data-bs-toggle="modal"
                        data-bs-target="#sudahDaftarModal">
                        Pendaftaran NCAGE
                        </a>
                    @else
                        <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}"
                        class="btn rounded-4 fw-bold fs-7">
                        Pendaftaran NCAGE
                        </a>
                    @endif
                </li>
                <li>
                    <a class="btn fw-bold fs-7 {{ Request::is('pantau-status*') ? 'text-white bg-active' : '' }}" href="{{ route('tracking.index') }}">
                        Pantau Status
                    </a>
                </li>
                <li>
                    <a class="btn fw-bold fs-7 {{ Request::is('cek-entitas*') ? 'text-white bg-active' : '' }}" href="{{ route('entity-check.index') }}">
                        Cek Entitas
                    </a>
                </li>
            </ul>
        </div>

        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="ms-auto">
                @guest
                <div class="d-flex gap-1 justify-content-center mt-3 mt-lg-0">
                    <a class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2" href="{{ route('login') }}">Masuk</a>
                    <a class="btn bg-active text-white rounded-pill px-4 py-2" href="{{ route('register') }}">Daftar</a>
                </div>
                @endguest

                @auth
                    {{-- Kita bungkus ikon notifikasi dan profil dalam sebuah list <ul> agar rapi --}}
                    <ul class="navbar-nav d-flex flex-row align-items-center">

                        {{-- 1. IKON NOTIFIKASI ANDA YANG DIMODIFIKASI --}}
                        <li class="nav-item dropdown notification-dropdown"> 
                            <a href="#" class="nav-link text-secondary" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-bell fs-5"></i>
                                <span class="notification-badge badge bg-primary rounded-pill" id="notification-count" style="display: none;"></span>
                            </a>

                            @include('partials.notification-menu')
                        </li>

                        {{-- 2. DROPDOWN PROFIL PENGGUNA --}}
                        <li class="nav-item dropdown ms-2">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil" class="profile-image">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8C1515&color=fff&rounded=true&size=40" alt="Foto Profil" class="profile-image">
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-header-custom text-center">
                                    <h6>Pengaturan</h6>
                                    <hr class="my-1">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fa-solid fa-user me-2"></i>Akun
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endauth

            </div>
        </div>
    </div>
</nav>