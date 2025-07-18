<div class="offcanvas offcanvas-end bg-dark-custom text-white" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">

  <div class="offcanvas-header">
    {{-- Tombol close sekarang berada di luar div profile --}}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    @auth
      <div class="profile-header-mobile text-center mb-4">
        {{-- Logika untuk menampilkan foto asli atau avatar --}}
        @if (Auth::user()->profile_photo_path)
            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                 alt="Foto Profil" class="offcanvas-profile-img">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8C1515&color=fff&rounded=true" 
                 alt="Foto Profil" class="offcanvas-profile-img">
        @endif
        <h5 class="mt-2 mb-0">{{ Auth::user()->name }}</h5>
      </div>
    @endauth

    <hr class="mx-3">
    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
      <li class="nav-item">
        <a class="nav-link {{ Request::is('/', 'beranda') ? 'active-mobile' : '' }}" href="{{ route('home') }}">Beranda</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Request::is('pendaftaran-ncage*') ? 'active-mobile' : '' }}" href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}">Pendaftaran NCAGE</a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Request::is('pantau-status*') ? 'active-mobile' : '' }}" href="{{ route('tracking.index') }}">Pantau Status</a>
      </li>

      <hr class="mx-3"> 
      
      @auth
        <li class="nav-item">
          <a class="nav-link" href="#">Notifikasi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('profile*') ? 'active-mobile' : '' }}" href="{{ route('profile.show') }}">Akun</a>
        </li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
              Logout
            </a>
          </form>
        </li>
      @endauth
      
      @guest
        <li class="nav-item mt-4">
          <a class="nav-link" href="{{ route('login') }}">Masuk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('register') }}">Daftar</a>
        </li>
      @endguest
    </ul>
  </div>
</div>