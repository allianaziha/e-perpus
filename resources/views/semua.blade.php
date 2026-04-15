<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>
    @if(isset($selectedCategory))
      Buku Kategori {{ $selectedCategory->nama }} - e-Perpus
    @else
      Perpustakaan Digital - e-Perpus
    @endif
  </title>

  <link rel="shortcut icon" type="image/png"
        href="{{ asset('assets/backend/images/logos/logo-mini.png') }}" />

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/buku.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  @include('layouts.components-frontend.navbar')

  {{-- ── SEARCH & KATEGORI SECTION ── --}}
  <div class="search-bar-section">
    <div class="search-bar-inner">

      {{-- SEARCH INPUT --}}
      <form action="{{ route('buku.index') }}" method="GET" id="searchForm">
        <div class="search-box mt-3">
          <i class="bi bi-search"></i>
          <input type="text"
                 id="searchInput"
                 name="search"
                 placeholder="Cari judul buku, penulis, atau kategori..."
                 value="{{ request('search') }}"
                 autocomplete="off">
          <span class="search-loader" id="searchLoader">
            <i class="bi bi-arrow-repeat spin"></i>
          </span>
          <a href="{{ route('buku.index') }}" class="clear-btn" id="clearBtn"
             style="{{ request('search') ? '' : 'display:none' }}" title="Hapus pencarian">
            <i class="bi bi-x-circle-fill"></i>
          </a>
        </div>
      </form>

      {{-- PILLS KATEGORI (tengah) --}}
      <div class="pill-row">
        <a href="{{ route('buku.index') }}"
           class="pill-kategori {{ !isset($selectedCategory) ? 'active' : '' }}">
          <i class="bi bi-grid-fill"></i> Semua
        </a>
        @foreach ($kategori as $cat)
          <a href="{{ route('buku.filter', $cat->id) }}"
             class="pill-kategori {{ isset($selectedCategory) && $selectedCategory->id == $cat->id ? 'active' : '' }}">
            {{ $cat->nama }}
          </a>
        @endforeach
      </div>

    </div>
  </div>

  {{-- ── KONTEN UTAMA ── --}}
  <div class="container mt-4 mb-5">
    <div class="col-12">

      {{-- PAGE HEADER --}}
      <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0" id="pageTitle">
          @if(isset($selectedCategory))
            <i class="bi bi-tag-fill me-1 text-primary"></i> {{ $selectedCategory->nama }}
          @else
            <i class="bi bi-books me-1 text-primary"></i> Koleksi Buku Perpustakaan
          @endif
        </h4>
        <span class="result-count" id="resultCount">{{ $buku->total() }} buku ditemukan</span>
      </div>

      {{-- GRID BUKU --}}
      <div id="bookGrid">
        @if($buku->count())
          <div class="book-grid">
            @foreach ($buku as $data)
              <div class="book-card">
                <div class="book-thumb">
                  <img src="{{ asset('images/buku/'.$data->gambar) }}"
                       alt="{{ $data->judul }}"
                       loading="lazy"
                       onerror="this.src='{{ asset('assets/frontend/images/no-cover.png') }}'">
                  <div class="book-overlay">
                    <a href="{{ route('buku.detail', $data->id) }}"
                       class="ov-btn" title="Lihat Detail">
                      <i class="bi bi-eye"></i>
                    </a>
                    <button class="ov-btn btn-cart"
                            title="Tambah ke Keranjang"
                            onclick="tambahKeranjang({{ $data->id }}, '{{ addslashes($data->judul) }}')">
                      <i class="bi bi-bag-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-search"></i></div>
            <p>Buku tidak ditemukan</p>
            <small>Coba kata kunci atau kategori yang berbeda</small>
          </div>
        @endif
      </div>

      {{-- PAGINATION --}}
      <div class="mt-4" id="paginationArea">
        {{ $buku->withQueryString()->links() }}
      </div>

    </div>
  </div>

  @include('layouts.components-frontend.footer')

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  {{-- TOAST NOTIFIKASI --}}
  <div class="toast-custom" id="toastMsg">
    <i class="bi bi-check-circle-fill" id="toastIcon"></i>
    <span id="toastText">Ditambahkan ke keranjang!</span>
  </div>

  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/js/main.js') }}"></script>

  <script>
    // ── REALTIME SEARCH ──────────────────────────────────────────────────
    let searchTimeout = null;

    const searchInput = document.getElementById('searchInput');
    const searchLoader = document.getElementById('searchLoader');
    const clearBtn = document.getElementById('clearBtn');
    const bookGrid = document.getElementById('bookGrid');
    const resultCount = document.getElementById('resultCount');
    const paginationArea = document.getElementById('paginationArea');

    searchInput.addEventListener('input', function () {
      const query = this.value.trim();

      // Tampilkan/sembunyikan tombol clear
      clearBtn.style.display = query ? '' : 'none';

      clearTimeout(searchTimeout);

      if (query.length === 0) {
        // Kalau kosong, reload halaman bersih
        window.location.href = '{{ route('buku.index') }}';
        return;
      }

      // Debounce 300ms baru fetch
      searchTimeout = setTimeout(() => {
        doSearch(query);
      }, 300);
    });

    function doSearch(query) {
      searchLoader.style.display = 'flex';

      fetch(`{{ route('buku.index') }}?search=${encodeURIComponent(query)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(res => res.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Ambil konten grid dari response
        const newGrid = doc.getElementById('bookGrid');
        const newCount = doc.getElementById('resultCount');
        const newPagination = doc.getElementById('paginationArea');

        if (newGrid) bookGrid.innerHTML = newGrid.innerHTML;
        if (newCount) resultCount.textContent = newCount.textContent;
        if (newPagination) paginationArea.innerHTML = newPagination.innerHTML;

        searchLoader.style.display = 'none';
      })
      .catch(err => {
        console.error('Search error:', err);
        searchLoader.style.display = 'none';
      });
    }

    // ── TAMBAH KERANJANG ──────────────────────────────────────────────────
    function tambahKeranjang(id, judul) {
      @auth
        fetch(`/chart-pinjam/add/${id}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ qty: 1 })
        })
        .then(res => res.json())
        .then(data => {
          showToast(data.message ?? `"${judul}" ditambahkan ke keranjang!`, 'success');
        })
        .catch(err => {
          console.error('Error:', err);
          showToast('Gagal menambahkan ke keranjang. Silakan coba lagi.', 'warning');
        });
      @else
        showToast('Silakan login terlebih dahulu untuk meminjam buku.', 'warning');
        setTimeout(() => { window.location.href = '{{ route('login') }}'; }, 1500);
      @endauth
    }

    function showToast(msg, type = 'success') {
      const toast = document.getElementById('toastMsg');
      const text  = document.getElementById('toastText');
      const icon  = document.getElementById('toastIcon');

      text.textContent = msg;
      toast.classList.remove('toast-warning');

      if (type === 'warning') {
        toast.classList.add('toast-warning');
        icon.className = 'bi bi-exclamation-circle-fill';
      } else {
        icon.className = 'bi bi-check-circle-fill';
      }

      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2500);
    }
  </script>

</body>
</html>