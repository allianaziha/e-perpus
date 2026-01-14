@php
  use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>{{ $buku->judul }} - e-Perpus</title>

  <!-- Favicons -->
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/backend/images/logos/logo-mini.png') }}" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS -->
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  {{-- Navbar --}}
  @include('layouts.components-frontend.navbar')

  {{-- DETAIL BUKU --}}
  <main class="main" style="margin-top:100px; background:linear-gradient(180deg,#ffffff 70%,#ccebff 100%);">
    <section class="py-4">
      <div class="container">
        <div class="row align-items-start g-5">

          <!-- Cover -->
          <div class="col-md-4">
            <div class="p-3 rounded-4 text-center" style="background:#f8f9fa;">
              <img src="{{ asset('images/buku/' . $buku->gambar) }}"
                   class="img-fluid rounded-4"
                   style="max-height:500px; object-fit:contain;"
                   alt="{{ $buku->judul }}">
            </div>
          </div>

          <!-- Info -->
          <div class="col-md-8 d-flex flex-column justify-content-center">
            <h2 class="fw-bold mb-2">{{ $buku->judul }}</h2>

            <div class="d-flex flex-wrap gap-2 mb-3">
              <span class="badge rounded-pill bg-light text-dark border">✍️ {{ $buku->penulis ?? '-' }}</span>
              <span class="badge rounded-pill bg-light text-dark border">🏢 {{ $buku->penerbit ?? '-' }}</span>
              <span class="badge rounded-pill bg-light text-dark border">📅 {{ $buku->tahun_terbit ?? '-' }}</span>
            </div>

            <div class="p-3 rounded-4 mb-3" style="background:#f8fbff;">
              <h6 class="fw-semibold mb-1">Deskripsi Buku</h6>
              <p class="mb-0">{{ $buku->deskripsi ?? 'Belum ada deskripsi buku.' }}</p>
            </div>

            {{-- PINJAM --}}
            @auth
            <form action="{{ route('peminjaman.store') }}" method="POST">
              @csrf
              <input type="hidden" name="buku_id" value="{{ $buku->id }}">

              <div class="mb-3">
                <div class="d-flex align-items-center border rounded-pill bg-white" style="width:120px;">
                  <button type="button" id="qtyMinus" class="btn btn-link px-2 text-dark">
                    <i class="bi bi-dash"></i>
                  </button>
                  <input type="number" name="jumlah_buku" id="qtyInput"
                         class="form-control border-0 text-center p-0"
                         value="1" min="1" max="{{ $buku->stok }}">
                  <button type="button" id="qtyPlus" class="btn btn-link px-2 text-dark">
                    <i class="bi bi-plus"></i>
                  </button>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button class="btn btn-primary rounded-pill px-4">
                  <i class="bi bi-book me-1"></i> Pinjam
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4">
                  Kembali
                </a>
              </div>
            </form>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">
                Login untuk Pinjam
              </a>
            @endauth
          </div>
        </div>
      </div>
    </section>

    {{-- REKOMENDASI --}}
    <section class="py-5" style="background:#f8fbff;">
      <div class="container">
        <h4 class="fw-bold mb-4">📚 Rekomendasi Buku Lainnya</h4>

        <div class="row g-4">
          @forelse ($rekomendasi as $item)
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm rounded-4"
                 style="transition:.3s"
                 onmouseover="this.style.transform='translateY(-6px)'"
                 onmouseout="this.style.transform='none'">

              <div class="text-center p-3" style="background:#f8f9fa;">
                <img src="{{ asset('images/buku/' . $item->gambar) }}"
                     class="img-fluid"
                     style="max-height:180px; object-fit:contain;">
              </div>

              <div class="card-body d-flex flex-column">
                <h6 class="fw-semibold mb-1">{{ Str::limit($item->judul, 38) }}</h6>
                <small class="text-muted mb-3">{{ $item->penulis }}</small>

                <a href="{{ route('buku.detail', $item->id) }}"
                   class="btn btn-outline-primary btn-sm rounded-pill mt-auto">
                  Lihat Detail
                </a>
              </div>
            </div>
          </div>
          @empty
            <p class="text-muted">Belum ada rekomendasi buku.</p>
          @endforelse
        </div>
      </div>
    </section>
  </main>

  {{-- Footer --}}
  @include('layouts.components-frontend.footer')

  <!-- JS -->
  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    const qtyInput = document.getElementById('qtyInput');
    document.getElementById('qtyMinus').onclick = () => qtyInput.value > 1 ? qtyInput.value-- : null;
    document.getElementById('qtyPlus').onclick = () => qtyInput.value < qtyInput.max ? qtyInput.value++ : null;
  </script>

  @include('sweetalert::alert')
</body>
</html>
