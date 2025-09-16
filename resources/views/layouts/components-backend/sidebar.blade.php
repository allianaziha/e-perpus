<aside class="left-sidebar with-vertical">
  <div>
    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ url('/home') }}" class="text-nowrap logo-img d-flex align-items-center">
        <img src="{{ asset('assets/backend/images/logos/logo-perpus.png') }}" 
            alt="" width="180" class=""/>
      </a>
      <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
        <i class="ti ti-x"></i>
      </a>
    </div>

    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">

        {{-- Menu untuk Admin --}}
        @if(Auth::user()->role == 'admin')
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
              <i class="ti ti-home me-2"></i>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.user.index') }}">
              <i class="ti ti-users"></i>
              <span class="hide-menu">User</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.kategori.index') }}">
              <i class="ti ti-folders"></i>
              <span class="hide-menu">Kategori</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.rak.index') }}">
              <i class="ti ti-books"></i>
              <span class="hide-menu">Rak</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.buku.index') }}">
              <i class="ti ti-book-2"></i>
              <span class="hide-menu">Buku</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.peminjaman.index') }}">
              <i class="ti ti-book-upload"></i>
              <span class="hide-menu">Peminjaman</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.pengembalian.index') }}">
              <i class="ti ti-book-download"></i>
              <span class="hide-menu">Pengembalian</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.denda.index') }}">
              <i class="ti ti-cash"></i>
              <span class="hide-menu">Denda</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.laporan.index') }}">
              <i class="ti ti-report-money"></i>
              <span class="hide-menu">Laporan</span>
            </a>
          </li>
        @endif

        {{-- Menu untuk Petugas --}}
        @if(Auth::user()->role == 'petugas')
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('petugas.dashboard') }}">
              <i class="ti ti-home me-2"></i>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('petugas.buku.index') }}">
              <i class="ti ti-book-2"></i>
              <span class="hide-menu">Data Buku</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('petugas.peminjaman.index') }}">
              <i class="ti ti-book-upload"></i>
              <span class="hide-menu">Peminjaman</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('petugas.pengembalian.index') }}">
              <i class="ti ti-book-download"></i>
              <span class="hide-menu">Pengembalian</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('petugas.denda.index') }}">
              <i class="ti ti-cash"></i>
              <span class="hide-menu">Denda</span>
            </a>
          </li>
        @endif

      </ul>
    </nav>
    <!-- ---------------------------------- -->
    <!-- End Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->
  </div>
</aside>
