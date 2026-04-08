@php
  use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ $buku->judul }} - e-Perpus</title>

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
  <link href="{{ asset('assets/frontend/css/detail-buku.css') }}" rel="stylesheet">
</head>

<body>

{{-- Navbar --}}
@include('layouts.components-frontend.navbar')



<!-- ================= CONTENT ================= -->
<div class="container" style="margin-top:100px">

  <!-- DETAIL -->
  <div class="book-detail">

    <!-- COVER -->
    <div class="book-cover">
      <div class="stock-badge">Tersedia: {{ $buku->stok }}</div>
      <div class="cover-wrapper">
        @if($buku->gambar)
          <img src="{{ asset('images/buku/'.$buku->gambar) }}"
               class="img-fluid rounded"
               alt="{{ $buku->judul }}">
        @else
          <div class="placeholder-cover">📖</div>
        @endif
      </div>
    </div>

    <!-- INFO -->
    <div class="book-info">
      <h1 class="book-title">{{ $buku->judul }}</h1>

      <div class="book-meta">
        <span class="meta-badge">✍️ {{ $buku->penulis ?? '-' }}</span>
        <span class="meta-badge">🏢 {{ $buku->penerbit ?? '-' }}</span>
        <span class="meta-badge">📅 {{ $buku->tahun_terbit ?? '-' }}</span>
        <span class="meta-badge">📚 {{ $buku->kategori->nama ?? '-' }}</span>
      </div>

      <div class="description-box">
        <h3>Deskripsi Buku</h3>
        <p>{{ $buku->deskripsi ?? 'Belum ada deskripsi buku.' }}</p>
      </div>

      {{-- JUMLAH --}}
      <div class="borrow-section">
        <div class="quantity-label">Jumlah Buku</div>
        <div class="quantity-selector">
          <button class="qty-btn" onclick="decreaseQty()">−</button>
          <input type="number" id="qtyInput"
                 class="qty-input"
                 value="1"
                 min="1"
                 max="{{ $buku->stok }}"
                 readonly>
          <button class="qty-btn" onclick="increaseQty()">+</button>
        </div>
      </div>

      {{-- AKSI --}}
      <div class="action-buttons">
  @auth
  {{-- Tombol Favorit --}}
  <button type="button"
          class="btn {{ $buku->isFavoritByUser() ? 'btn-danger' : 'btn-outline-danger' }} me-2"
          id="favoritBtn"
          onclick="toggleFavorit({{ $buku->id }})">
    <i class="bi {{ $buku->isFavoritByUser() ? 'bi-heart-fill' : 'bi-heart' }}"></i>
    {{ $buku->isFavoritByUser() ? 'Hapus Favorit' : 'Tambah Favorit' }}
  </button>

  <form id="addToCartForm"
        action="{{ route('chart.pinjam.add', $buku) }}"
        method="POST"
        style="display:inline;">
    @csrf
    <input type="hidden" name="qty" id="qtyHidden" value="1">
    <button type="button" class="btn btn-primary" onclick="addToCart(event)">
      🛒 Masukkan ke Keranjang
    </button>
  </form>
  @else
  <a href="{{ route('login') }}" class="btn btn-primary">
    Login untuk Pinjam
  </a>
  @endauth

  <a href="{{ url()->previous() }}" class="btn btn-secondary">
    ← Kembali
  </a>
</div>

    </div>
  </div>

  <!-- ================= REKOMENDASI ================= -->
  <div class="recommendations">
    <h2 class="section-title">📚 Rekomendasi Buku Lainnya</h2>

    <div class="books-grid">
      @forelse ($rekomendasi as $item)
      <div class="book-card">
        <div class="book-card-cover">
          <img src="{{ asset('images/buku/'.$item->gambar) }}"
               style="width:120px;height:180px;object-fit:contain;">
        </div>
        <div class="book-card-body">
          <h3 class="book-card-title">
            {{ Str::limit($item->judul, 8) }}
          </h3>
          <p class="book-card-author">{{ $item->penulis }}</p>
          <a href="{{ route('buku.detail', $item->id) }}"
             class="book-card-btn">
            Lihat Detail
          </a>
        </div>
      </div>
      @empty
        <p class="text-muted">Belum ada rekomendasi buku.</p>
      @endforelse
    </div>
  </div>
</div>

{{-- Footer --}}
@include('layouts.components-frontend.footer')

<!-- ================= JS ================= -->
<script>
const qtyInput = document.getElementById('qtyInput');
const qtyHidden = document.getElementById('qtyHidden');

function decreaseQty() {
  if (qtyInput.value > 1) {
    qtyInput.value--;
    qtyHidden.value = qtyInput.value;
  }
}
function increaseQty() {
  if (qtyInput.value < qtyInput.max) {
    qtyInput.value++;
    qtyHidden.value = qtyInput.value;
  }
}

function addToCart(event) {
  event.preventDefault();
  
  const form = document.getElementById('addToCartForm');
  const formData = new FormData(form);
  
  fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Update jumlah keranjang di navbar secara real-time
      const cartToggle = document.getElementById('cartToggle');
      let badge = document.getElementById('cartCountBadge');
      if (!badge) {
        badge = document.createElement('span');
        badge.id = 'cartCountBadge';
        badge.className = 'cart-count-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
        cartToggle.appendChild(badge);
      }
      badge.textContent = data.cart_count;

      Swal.fire({
        title: 'Berhasil!',
        text: 'Buku telah dimasukkan ke keranjang',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      });
    } else {
      Swal.fire({
        title: 'Gagal!',
        text: data.message || 'Terjadi kesalahan',
        icon: 'error'
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Swal.fire({
      title: 'Error!',
      text: 'Terjadi kesalahan saat menambahkan ke keranjang',
      icon: 'error'
    });
  });
}

function toggleFavorit(bukuId) {
  const btn = document.getElementById('favoritBtn');
  const icon = btn.querySelector('i');
  const text = btn.querySelector('span') || btn;

  // Disable button during request
  btn.disabled = true;
  const originalText = btn.innerHTML;

  fetch('{{ route("favorit.store") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ buku_id: bukuId })
  })
  .then(response => {
    return response.json().then(data => ({ status: response.status, ok: response.ok, data }));
  })
  .then(result => {
    const { ok, data } = result;

    if (ok) {
      if (data.action === 'added') {
        btn.classList.remove('btn-outline-danger');
        btn.classList.add('btn-danger');
        btn.innerHTML = '<i class="bi bi-heart-fill"></i> Hapus Favorit';
      } else if (data.action === 'removed') {
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-outline-danger');
        btn.innerHTML = '<i class="bi bi-heart"></i> Tambah Favorit';
      }

      Swal.fire({
        title: 'Berhasil!',
        text: data.message,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      });
    } else {
      Swal.fire({
        title: 'Gagal!',
        text: data.message || 'Terjadi kesalahan',
        icon: 'error'
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Swal.fire({
      title: 'Error!',
      text: 'Terjadi kesalahan saat memproses favorit',
      icon: 'error'
    });
  })
  .finally(() => {
    btn.disabled = false;
  });
}
</script>

<script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('sweetalert::alert')
</body>
</html>
