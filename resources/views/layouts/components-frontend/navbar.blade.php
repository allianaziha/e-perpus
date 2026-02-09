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
              <a class="dropdown-item" href="{{ url('/') }}#highlight-books">Buku</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('buku.semua') }}">Semua Buku</a>
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

      {{-- LOGIN / PROFILE --}}
      @guest
        <a class="btn-getstarted" href="{{ route('login') }}">Login</a>
      @else
        <div class="dropdown">

          {{-- AVATAR NAVBAR --}}
          <button class="btn p-0 border-0 bg-transparent"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false">

            @if(auth()->user()->avatar)
              <img src="{{ asset(auth()->user()->avatar) }}"
                   class="rounded-circle shadow-sm"
                   style="width:38px; height:38px; object-fit:cover;">
            @else
              <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white shadow-sm"
                   style="width:38px; height:38px; font-weight:bold;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
              </div>
            @endif

          </button>

          {{-- DROPDOWN --}}
          <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 p-3"
              style="border:none; min-width:200px;">

            {{-- DASHBOARD --}}
            @if(auth()->user()->role == 'admin')
              <li>
                <a class="dropdown-item py-2 px-3 rounded-3"
                   href="{{ route('admin.dashboard') }}">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
                </a>
              </li>
            @elseif(auth()->user()->role == 'petugas')
              <li>
                <a class="dropdown-item py-2 px-3 rounded-3"
                   href="{{ route('petugas.dashboard') }}">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
                </a>
              </li>
            @else
              <li>
                <a class="dropdown-item py-2 px-3 rounded-3"
                   href="{{ route('user.buku.index') }}">
                  <i class="bi bi-book me-2 text-primary"></i>Dashboard
                </a>
              </li>
            @endif

            <li><hr class="dropdown-divider my-2"></li>

            {{-- LOGOUT --}}
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="dropdown-item py-2 px-3 rounded-3 text-danger w-100 text-start">
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
