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
              <a class="dropdown-item" href="{{ url('/') }}#highlight-books">
                Buku
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('buku.semua') }}">
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

      {{-- LOGIN / DASHBOARD / LOGOUT --}}
      @guest
        <a class="btn-getstarted" href="{{ route('login') }}">Login</a>
      @else
        <div class="dropdown">
              <button class="btn-getstarted rounded-circle p-0 border-0 position-relative"
              type="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
              style="transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <i class="bi bi-person-circle fs-4 text-primary"></i>
              </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3" style="border: none; min-width: 180px;">
            @if(auth()->user()->role == 'admin')
              <li><a class="dropdown-item py-2 px-3" href="{{ route('admin.dashboard') }}" style="transition: all 0.2s ease;">
                <i class="bi bi-speedometer2 me-2 text-primary"></i><strong>Dashboard</strong>
              </a></li>
            @elseif(auth()->user()->role == 'petugas')
              <li><a class="dropdown-item py-2 px-3" href="{{ route('petugas.dashboard') }}" style="transition: all 0.2s ease;">
                <i class="bi bi-speedometer2 me-2 text-primary"></i><strong>Dashboard</strong>
              </a></li>
            @elseif(auth()->user()->role == 'user')
              <li><a class="dropdown-item py-2 px-3" href="{{ route('user.buku.index') }}" style="transition: all 0.2s ease;">
                <i class="bi bi-book me-2 text-primary"></i><strong>Dashboard</strong>
              </a></li>
            @endif
            <li><hr class="dropdown-divider my-1"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                @csrf
                <button type="submit" class="dropdown-item py-2 px-3 text-danger w-100 text-start" style="transition: all 0.2s ease;">
                  <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
              </form>
            </li>
          </ul>
        </div>
      @endguest
    </div>

  </div>

  {{-- MINI CART --}}
  @auth
  @include('layouts.components-frontend.minichart')
  @endauth
</header>
