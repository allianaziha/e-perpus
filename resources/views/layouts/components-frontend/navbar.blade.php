<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between bg-white bg-opacity-80 rounded-pill px-4 shadow-sm">

    {{-- LOGO --}}
    <a href="{{ url('/') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('assets/backend/images/logos/logo-mini.png') }}" alt="Logo Perpus" />
      <span class="fw-bold text-blue ms-2">E-Perpus</span>
    </a>

    {{-- NAVBAR --}}
    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="{{ url('/') }}#hero" class="active">Beranda</a></li>
        <li><a href="{{ url('/') }}#about">Tentang</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            Buku
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="{{ route('buku.index') }}">
                Semua Buku
              </a>
            </li>
          </ul>
        </li>
        <li><a href="{{ url('/') }}#contact">Kontak</a></li>
      </ul>
    </nav>

    {{-- KANAN HEADER --}}
    <div class="d-flex align-items-center gap-3">

      {{-- ICON KERANJANG --}}
      @auth
      @php
        $cartCount = \App\Models\ChartPinjam::where('user_id', auth()->id())->sum('qty');
      @endphp

      <button id="cartToggle"
              class="btn btn-light position-relative rounded-circle shadow-sm open-cart">
        <i class="bi bi-cart3 fs-5"></i>

        @if($cartCount > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $cartCount }}
          </span>
        @endif
      </button>
      @endauth

      {{-- LOGIN / DASHBOARD --}}
      @guest
        <a class="btn-getstarted" href="{{ route('login') }}">Login</a>
      @else
        <a class="btn-getstarted" href="">Dashboard</a>
      @endguest
    </div>

  </div>

  {{-- MINI CART --}}
  @auth
  @include('layouts.components-frontend.minichart')
  @endauth
</header>
