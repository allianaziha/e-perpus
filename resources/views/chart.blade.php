@php
  use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Keranjang Peminjaman - e-Perpus</title>

  <!-- Favicons -->
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/backend/images/logos/logo-mini.png') }}" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS -->
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/detail-buku.css') }}" rel="stylesheet">

</head>

<body>

  {{-- Navbar --}}
  @include('layouts.components-frontend.navbar')

  <main class="main">

<!-- cart__section__start -->
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Keranjang Peminjaman</h2>

            @if($chartPinjam->isEmpty())
                <div class="alert alert-info text-center">
                    <h4>Keranjang peminjaman masih kosong</h4>
                    <p>Silakan tambahkan buku ke keranjang untuk memulai peminjaman.</p>
                    <a href="{{ route('buku.index') }}" class="btn btn-primary">Cari Buku</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Cover</th>
                                <th>Judul Buku</th>
                                <th>Stok Tersedia</th>
                                <th>Jumlah Pinjam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chartPinjam as $item)
                            <tr>
                                <td>
                                    @if($item->buku && $item->buku->gambar)
                                        <img src="{{ asset('images/buku/'.$item->buku->gambar) }}"
                                             alt="{{ $item->buku->judul }}"
                                             class="img-thumbnail"
                                             style="width: 60px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 80px;">
                                            📖
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->buku->judul ?? 'Buku tidak ditemukan' }}</strong><br>
                                    <small class="text-muted">{{ $item->buku->penulis ?? '-' }}</small>
                                </td>
                                <td>{{ $item->buku->stok ?? 0 }} buku</td>
                                <td>
                                    <form action="{{ route('chart.pinjam.update', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <input type="number"
                                                   name="qty"
                                                   value="{{ $item->qty }}"
                                                   class="form-control"
                                                   min="1"
                                                   max="{{ $item->buku->stok ?? 1 }}">
                                            <button type="submit" class="btn btn-outline-secondary">Update</button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('chart.pinjam.remove', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus buku ini dari keranjang?')">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Ringkasan Peminjaman</h5>
                                <p><strong>Total Buku:</strong> {{ $chartPinjam->sum('qty') }} buku</p>
                                <p><strong>Jumlah Item:</strong> {{ $chartPinjam->count() }} jenis buku</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('chart.pinjam.checkout') }}" class="btn btn-success btn-lg">
                            <i class="fa fa-check"></i> Proses Peminjaman
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- cart__section__end -->

  </main>
  
  {{-- Footer --}}
  @include('layouts.components-frontend.footer')

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/js/main.js') }}"></script>

</body>
</html>