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

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png"
        href="{{ asset('assets/backend/images/logos/logo-mini.png') }}" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/buku.css') }}" rel="stylesheet">
  
</head>

<body class="index-page">

  {{-- Navbar --}}
  @include('layouts.components-frontend.navbar')

  <div class="container mt-4">
    <div class="row">

      <!-- SIDEBAR -->
      <div class="col-lg-3 col-md-4 mb-4">
        <div class="sidebar">
          <h5><i class="bi bi-journal-bookmark me-2"></i>Kategori Buku</h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item border-0 px-0">
              <a href="{{ route('buku.index') }}"
                 class="d-flex justify-content-between {{ !isset($selectedCategory) ? 'fw-bold text-primary' : '' }}">
                Semua Buku
                <span class="badge bg-primary">{{ $buku->total() }}</span>
              </a>
            </li>

            @foreach ($kategori as $cat)
              <li class="list-group-item border-0 px-0">
                <a href="{{ route('buku.filter', $cat->id) }}"
                   class="d-flex justify-content-between {{ isset($selectedCategory) && $selectedCategory->id == $cat->id ? 'fw-bold text-primary' : '' }}">
                  {{ $cat->nama }}
                  <span class="badge bg-light text-dark">{{ $cat->bukus_count }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <!-- CONTENT -->
      <div class="col-lg-9 col-md-8">

        <!-- HEADER + SEARCH -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
          <h4 class="mb-0">
            @if(isset($selectedCategory))
              Kategori: {{ $selectedCategory->nama }}
            @else
              Koleksi Buku Perpustakaan
            @endif
          </h4>

          <form action="{{ route('buku.index') }}" method="GET" class="d-flex">
            <input type="text"
                   name="search"
                   class="form-control me-2"
                   placeholder="Cari judul buku..."
                   value="{{ request('search') }}">
            <button class="btn btn-primary">
              <i class="bi bi-search"></i>
            </button>
          </form>
        </div>

        <!-- GRID BUKU -->
        <div class="row g-4">
          @forelse ($buku as $data)
            <div class="col-lg-4 col-md-6 d-flex justify-content-center">
              <div class="card book-card border-0 rounded-4 hover-zoom"
                   style="width:100%; max-width:260px; height:380px;">
                <a href="{{ route('buku.detail', $data->id) }}" class="d-block h-100">
                  <img src="{{ asset('images/buku/'.$data->gambar) }}"
                       alt="{{ $data->judul }}"
                       class="img-fluid w-100 h-100 object-fit-cover rounded-4">
                </a>
              </div>
            </div>
          @empty
            <p class="text-muted text-center">Buku tidak ditemukan.</p>
          @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
          {{ $buku->withQueryString()->links() }}
        </div>

      </div>
    </div>
  </div>

  {{-- Footer --}}
  @include('layouts.components-frontend.footer')

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/js/main.js') }}"></script>

</body>
</html>
