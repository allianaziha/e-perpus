<aside class="left-sidebar with-vertical">
      <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{ url('/home') }}" class="text-nowrap logo-img d-flex align-items-center">
            <img src="{{ asset('assets/backend/images/logos/perpus-logo.png') }}" 
                alt="Logo PERPUS" width="75" class="me-2"/>
            <span class="fw-bold fs-4 text-dark">PERPUS</span>
          </a>
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>


        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
          <ul id="sidebarnav">
            <!-- ---------------------------------- -->
            <!-- Home -->
            <!-- ---------------------------------- -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <!-- ---------------------------------- -->
            <!-- Dashboard -->
            <!-- ---------------------------------- -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('kategori.index') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-folders"></i>
                </span>
                <span class="hide-menu">Kategori</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('rak.index') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-books"></i>
                </span>
                <span class="hide-menu">Rak</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('buku.index') }}" aria-expanded="false">
                <span>
                <i class="ti ti-book-2"></i>
                </span>
                <span class="hide-menu">Buku</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('peminjaman.index') }}" aria-expanded="false">
                <span>
                <i class="ti ti-book-upload"></i>
                </span>
                <span class="hide-menu">Peminjaman</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('pengembalian.index') }}" aria-expanded="false">
                <span>
                <i class="ti ti-book-download"></i>
                </span>
                <span class="hide-menu">Pengembalian</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('denda.index') }}" aria-expanded="false">
                <span>
                <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu">Denda</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
      </div>
    </aside>