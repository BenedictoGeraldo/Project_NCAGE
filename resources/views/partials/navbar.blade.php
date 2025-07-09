<nav class="navbar px-4 py-3 position-relative align-items-center">
    <div class="logo text-white fs-4 position-relative z-1">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
    </div>
    <div class="position-absolute top-50 start-50 translate-middle">
        <ul class="d-flex list-unstyled gap-4 mb-0">
            <li><a href="#">Home</a></li>
            <li><a href="#">Pendaftaran NCAGE</a></li>
            <li><a href="#">Pantau Status</a></li>
        </ul>
    </div>
    <div class="ms-auto text-white position-relative z-1">
        <div class="btn d-flex gap-3">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>

    @auth
            {{-- TAMPILKAN INI HANYA JIKA PENGGUNA SUDAH LOGIN --}}
            
            {{-- Tombol Logout harus menggunakan form dengan method POST untuk keamanan --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    Logout
                </a>
            </form>
        @endauth
</nav>