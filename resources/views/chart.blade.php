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
  <link href="{{ asset('assets/frontend/css/cart.css') }}" rel="stylesheet">
  
</head>

<body>

  {{-- Navbar --}}
  @include('layouts.components-frontend.navbar')

  <main class="main">

<!-- cart__section__start -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="cart-header">
                <h2><i class="bi bi-cart3"></i> Keranjang Peminjaman</h2>
            </div>

            @if($chartPinjam->isEmpty())
                <div class="empty-cart">
                    <h4>📚 Keranjang Peminjaman Kosong</h4>
                    <p>Silakan tambahkan buku ke keranjang untuk memulai peminjaman Anda.</p>
                    <a href="{{ route('buku.index') }}" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari Buku
                    </a>
                </div>
            @else
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Cover</th>
                                    <th>Judul Buku</th>
                                    <th style="width: 180px;">Jumlah Pinjam</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chartPinjam as $item)
                                <tr>
                                    <td class="book-cover">
                                        @if($item->buku && $item->buku->gambar)
                                            <img src="{{ asset('images/buku/'.$item->buku->gambar) }}"
                                                 alt="{{ $item->buku->judul }}">
                                        @else
                                            <div class="book-placeholder">📖</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="book-title">{{ $item->buku->judul ?? 'Buku tidak ditemukan' }}</div>
                                        <div class="book-author">{{ $item->buku->penulis ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <form action="{{ route('chart.pinjam.update', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm" style="width: 130px;">
                                                <input type="number"
                                                       name="qty"
                                                       value="{{ $item->qty }}"
                                                       min="1"
                                                       max="{{ $item->buku->stok ?? 1 }}">
                                                <button type="submit" class="btn">Update</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('chart.pinjam.remove', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-delete"
                                                    onclick="return confirm('Hapus buku ini dari keranjang?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="summary-section">
                    <div class="summary-card">
                        <h5><i class="bi bi-info-circle"></i> Ringkasan Peminjaman</h5>
                        <div class="summary-item">
                            <span class="summary-label">Total Buku</span>
                            <span class="summary-value">{{ $chartPinjam->sum('qty') }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Jumlah Item</span>
                            <span class="summary-value">{{ $chartPinjam->count() }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Status</span>
                            <span class="status-badge"><i class="bi bi-check-circle"></i> Siap Diproses</span>
                        </div>
                    </div>
                    <div class="summary-card">
                        <h5><i class="bi bi-check-circle"></i> Lanjutkan Peminjaman</h5>
                        <p class="checkout-info">Periksa kembali daftar buku Anda sebelum memproses peminjaman. Pastikan semua item sudah benar.</p>
                        <a href="{{ route('chart.pinjam.checkout') }}" class="checkout-btn">
                            <i class="bi bi-check-lg"></i> Proses Peminjaman
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