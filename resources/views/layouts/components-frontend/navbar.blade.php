<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between bg-white bg-opacity-80 rounded-pill px-4 shadow-sm">
    
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('assets/backend/images/logos/logo-mini.png') }}" alt="Logo Perpus" class="logo-full" />
      <span class="fw-bold text-blue">E-Perpus</span>
    </a>

    {{-- Menu Navigasi --}}
    <nav id="navmenu" class="navmenu">
      <ul>
        <li>
          <a href="{{ url('/') }}#hero" class="active">
            Beranda
          </a>
        </li>

        <li>
          <a href="{{ url('/') }}#about">
            Tentang
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" 
            href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Buku
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="nav-link {{ request()->routeIs('buku-semua') ? 'active' : '' }}" href="{{ route('buku.index') }}">Semua Buku</a></li>
            <li><a class="dropdown-item" href="{{ url('/') }}#highlight-books">Buku</a></li>
          </ul>
        </li>

        <li><a class="nav-link {{ request()->routeIs('riwayat.peminjaman') ? 'active' : '' }}" href="{{ route('riwayat.peminjaman') }}">Riwayat</a></li>
        <li><a href="{{ url('/') }}#contact">Kontak</a></li>
      </ul>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>

      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    @guest
      <a class="btn-getstarted" href="{{ route('login') }}">Login</a>
    @else
      <a class="btn-getstarted" href="{{ route('logout') }}" 
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
      </a>
    @endguest

  </div>
</header>
