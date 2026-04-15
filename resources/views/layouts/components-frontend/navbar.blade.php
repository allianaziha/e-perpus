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
        <li>
          <a href="{{ url('/') }}#hero"
             class="{{ request()->is('/') ? 'active' : '' }}">
            Beranda
          </a>
        </li>

        <li>
          <a href="{{ url('/') }}#about"
             class="{{ request()->is('/') ? 'active' : '' }}">
            Tentang
          </a>
        </li>

        {{-- DROPDOWN BUKU --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->is('buku*') || request()->routeIs('buku.*') ? 'active' : '' }}"
             href="#"
             data-bs-toggle="dropdown">
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
      </ul>
    </nav>

    {{-- KANAN HEADER --}}
    <div class="d-flex align-items-center gap-3">

      @auth
      @php
        $cartCount = \App\Models\ChartPinjam::where('user_id', auth()->id())->count();
        $favoritCount = \App\Models\FavoritBuku::where('user_id', auth()->id())->count();
      @endphp

      <div class="d-flex gap-2 align-items-center">

        {{-- FAVORIT --}}
        <a href="{{ route('favorit.index') }}"
           class="btn btn-light position-relative rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0"
           style="width: 40px; height: 40px; padding: 0;"
           title="Buku Favorit"
           id="favorit-btn">
          <i class="bi bi-heart fs-5 text-danger"></i>
          {{-- 
            FIX #3: Badge favorit update realtime
            - Beri id="favorit-count" supaya bisa di-update via JS tanpa refresh
          --}}
          @if($favoritCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  id="favorit-count"
                  style="font-size: 10px;">
              {{ $favoritCount }}
            </span>
          @else
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                  id="favorit-count"
                  style="font-size: 10px;">
              0
            </span>
          @endif
        </a>

        {{-- KERANJANG --}}
        <button id="cartToggle"
                class="btn btn-light position-relative rounded-circle shadow-sm open-cart d-flex align-items-center justify-content-center flex-shrink-0"
                style="width: 40px; height: 40px; padding: 0;"
                title="Keranjang Pinjam">
          <i class="bi bi-cart3 fs-5"></i>
          @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  id="cart-count"
                  style="font-size: 10px;">
              {{ $cartCount }}
            </span>
          @else
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                  id="cart-count"
                  style="font-size: 10px;">
              0
            </span>
          @endif
        </button>
      </div>
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
                   style="width: 38px; height: 38px; object-fit: cover;">
            @else
              <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white shadow-sm"
                   style="width: 38px; height: 38px; font-weight: bold; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
              </div>
            @endif

          </button>

          {{-- DROPDOWN PROFILE --}}
          <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 p-3"
              style="border: none; min-width: 200px;">

            {{-- DASHBOARD --}}
            @if(auth()->user()->role == 'admin')
              <li>
                <a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('admin.dashboard') }}">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
                </a>
              </li>
            @elseif(auth()->user()->role == 'petugas')
              <li>
                <a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('petugas.dashboard') }}">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
                </a>
              </li>
            @else
              <li>
                <a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('user.buku.index') }}">
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
@auth
<script>
  // ── HELPER: update badge di navbar ──────────────────────────────
  function updateBadge(badgeId, count) {
    const badge = document.getElementById(badgeId);
    if (!badge) return;
    if (count > 0) {
      badge.textContent = count;
      badge.classList.remove('d-none');
    } else {
      badge.classList.add('d-none');
    }
  }

  // ── FAVORIT TOGGLE (realtime, tanpa refresh) ─────────────────────
  // Delegasi event supaya bekerja walau tombol di-render belakangan
  document.addEventListener('click', async function (e) {
    const btn = e.target.closest('[data-favorit-url]');
    if (!btn) return;

    e.preventDefault();

    try {
      const res = await fetch(btn.dataset.favoritUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      });

      if (!res.ok) throw new Error('Gagal toggle favorit');

      const data = await res.json();

      // Update badge navbar
      updateBadge('favorit-count', data.count);

      // Toggle icon hati (opsional, kalau tombolnya punya .bi-heart / .bi-heart-fill)
      const icon = btn.querySelector('.bi');
      if (icon) {
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
      }

      // Toggle teks tombol jika ada (opsional)
      if (btn.dataset.textToggle) {
        const isFav = icon?.classList.contains('bi-heart-fill');
        btn.querySelector('[data-label]')?.setAttribute('data-label', isFav ? 'Hapus Favorit' : 'Tambah Favorit');
      }

    } catch (err) {
      console.error('Favorit error:', err);
    }
  });

  // ── KERANJANG TAMBAH (realtime, tanpa refresh) ───────────────────
  // Delegasi event untuk tombol tambah keranjang
  document.addEventListener('click', async function (e) {
    const btn = e.target.closest('[data-cart-url]');
    if (!btn) return;

    e.preventDefault();

    try {
      const res = await fetch(btn.dataset.cartUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ buku_id: btn.dataset.bukuId }),
      });

      if (!res.ok) throw new Error('Gagal tambah keranjang');

      const data = await res.json();

      // Update badge navbar
      updateBadge('cart-count', data.count);

    } catch (err) {
      console.error('Cart error:', err);
    }
  });
</script>
@endauth