@php
  use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Keranjang Peminjaman - e-Perpus</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/backend/images/logos/logo-mini.png') }}" />
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/css/cart.css') }}" rel="stylesheet">
</head>

<body>
  @include('layouts.components-frontend.navbar')

  <main class="main">
    <div class="container">
      <div class="row">
        <div class="col-12">

          {{-- HEADER --}}
          <div class="cart-header">
            <div class="cart-header__left">
              <h2><i class="bi bi-bag-check"></i> Keranjang Peminjaman</h2>
              @if(!$chartPinjam->isEmpty())
                <span class="cart-badge">{{ $chartPinjam->count() }} item</span>
              @endif
            </div>
            <a href="{{ route('buku.index') }}" class="back-link">
              <i class="bi bi-arrow-left"></i> Lanjut cari buku
            </a>
          </div>

          @if($chartPinjam->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="empty-cart">
              <div class="empty-icon">
                <i class="bi bi-bag-x"></i>
              </div>
              <h5>Keranjang masih kosong</h5>
              <p>Tambahkan buku yang ingin kamu pinjam ke keranjang dulu ya.</p>
              <a href="{{ route('buku.index') }}" class="btn-primary-custom">
                <i class="bi bi-search"></i> Cari Buku
              </a>
            </div>

          @else
            <div class="cart-layout">

              {{-- KIRI: DAFTAR BUKU --}}
              <div class="cart-items-col">

                {{-- SELECT ALL --}}
                <div class="select-all-bar">
                  <label class="custom-check">
                    <input type="checkbox" id="select-all" checked>
                    <span class="checkmark"></span>
                    Pilih Semua
                  </label>
                  <span class="item-count-label" id="selectedCount">{{ $chartPinjam->count() }} dipilih</span>
                </div>

                {{-- LIST ITEM --}}
                <div class="cart-list" id="cartList">
                  @foreach ($chartPinjam as $item)
                    <div class="cart-item" data-id="{{ $item->id }}">

                      {{-- Checkbox --}}
                      <label class="custom-check item-check">
                        <input type="checkbox" name="selected_items[]"
                               value="{{ $item->id }}"
                               class="select-item" checked>
                        <span class="checkmark"></span>
                      </label>

                      {{-- Cover --}}
                      <div class="item-cover">
                        @if($item->buku && $item->buku->gambar)
                          <img src="{{ asset('images/buku/'.$item->buku->gambar) }}"
                               alt="{{ $item->buku->judul }}">
                        @else
                          <div class="cover-placeholder"><i class="bi bi-book"></i></div>
                        @endif
                      </div>

                      {{-- Info --}}
                      <div class="item-info">
                        <div class="item-title">{{ $item->buku->judul ?? 'Buku tidak ditemukan' }}</div>
                        <div class="item-author">
                          <i class="bi bi-person"></i> {{ $item->buku->penulis ?? '-' }}
                        </div>
                        <div class="item-stock">
                          <i class="bi bi-layers"></i> Stok: {{ $item->buku->stok ?? 0 }} tersedia
                        </div>
                      </div>

                      {{-- QTY Control --}}
                      <div class="qty-wrapper">
                        <button type="button"
                                class="qty-btn qty-minus"
                                data-id="{{ $item->id }}"
                                data-min="1">
                          <i class="bi bi-dash"></i>
                        </button>
                        <input type="number"
                               class="qty-input"
                               id="qty-{{ $item->id }}"
                               value="{{ $item->qty }}"
                               min="1"
                               max="{{ $item->buku->stok ?? 1 }}"
                               readonly>
                        <button type="button"
                                class="qty-btn qty-plus"
                                data-id="{{ $item->id }}"
                                data-max="{{ $item->buku->stok ?? 1 }}">
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>

                      {{-- Hapus --}}
                      <button type="button"
                              class="btn-remove"
                              data-id="{{ $item->id }}"
                              title="Hapus dari keranjang">
                        <i class="bi bi-trash3"></i>
                      </button>

                    </div>
                  @endforeach
                </div>

              </div>

              {{-- KANAN: SUMMARY --}}
              <div class="cart-summary-col">
                <div class="summary-card sticky-summary">
                  <h6 class="summary-title">
                    <i class="bi bi-receipt"></i> Ringkasan
                  </h6>

                  <div class="summary-rows">
                    <div class="summary-row">
                      <span>Total Item</span>
                      <strong id="summaryItems">{{ $chartPinjam->count() }}</strong>
                    </div>
                    <div class="summary-row">
                      <span>Total Buku</span>
                      <strong id="summaryQty">{{ $chartPinjam->sum('qty') }}</strong>
                    </div>
                    <div class="summary-row">
                      <span>Status</span>
                      <span class="status-pill"><i class="bi bi-check-circle-fill"></i> Siap</span>
                    </div>
                  </div>

                  <div class="summary-info">
                    <i class="bi bi-info-circle"></i>
                    Buku yang dipinjam akan dikembalikan dalam <strong>7 hari</strong>.
                  </div>

                  {{-- Form checkout selected --}}
                  <form action="{{ route('user.peminjaman.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div id="selectedItemsContainer"></div>
                    <button type="submit" class="btn-checkout" id="btnCheckout">
                      <i class="bi bi-check-lg"></i> Proses Buku Terpilih
                    </button>
                  </form>

                  <a href="{{ route('chart.pinjam.checkout') }}" class="btn-checkout-all">
                    <i class="bi bi-arrow-right-circle"></i> Proses Semua
                  </a>

                  @error('selected_items')
                    <div class="alert alert-danger mt-2 p-2 small">{{ $message }}</div>
                  @enderror
                </div>
              </div>

            </div>{{-- /.cart-layout --}}

            {{-- Hidden forms untuk update & remove --}}
            @foreach ($chartPinjam as $item)
              <form id="update-form-{{ $item->id }}"
                    action="{{ route('chart.pinjam.update', $item->id) }}"
                    method="POST" class="d-none">
                @csrf @method('PUT')
                <input type="hidden" name="qty" id="update-qty-{{ $item->id }}" value="{{ $item->qty }}">
              </form>
              <form id="remove-form-{{ $item->id }}"
                    action="{{ route('chart.pinjam.remove', $item->id) }}"
                    method="POST" class="d-none">
                @csrf @method('DELETE')
              </form>
            @endforeach

          @endif
        </div>
      </div>
    </div>
  </main>

  @include('layouts.components-frontend.footer')

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/js/main.js') }}"></script>

  <script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = '{{ csrf_token() }}';

  // ── SELECT ALL ─────────────────────────────────────────────────────
  const selectAll   = document.getElementById('select-all');
  const selectItems = document.querySelectorAll('.select-item');

  function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.select-item:checked');
    const checkedCount = checkedBoxes.length;

    // Update label "X dipilih"
    document.getElementById('selectedCount').textContent = checkedCount + ' dipilih';

    // Hitung Total Item & Total Buku dari item yang diceklis saja
    let totalQtySelected = 0;
    checkedBoxes.forEach(cb => {
      const itemId  = cb.value;
      const qtyInput = document.getElementById('qty-' + itemId);
      totalQtySelected += parseInt(qtyInput ? qtyInput.value : 0) || 0;
    });

    document.getElementById('summaryItems').textContent = checkedCount;
    document.getElementById('summaryQty').textContent   = totalQtySelected;

    // Sync hidden inputs ke form checkout
    const container = document.getElementById('selectedItemsContainer');
    container.innerHTML = '';
    checkedBoxes.forEach(cb => {
      const inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = 'selected_items[]';
      inp.value = cb.value;
      container.appendChild(inp);
    });

    // Disable tombol "Proses Buku Terpilih" kalau tidak ada yang dipilih
    const btnCheckout = document.getElementById('btnCheckout');
    if (btnCheckout) {
      btnCheckout.disabled = checkedCount === 0;
      btnCheckout.style.opacity = checkedCount === 0 ? '0.5' : '1';
      btnCheckout.style.cursor  = checkedCount === 0 ? 'not-allowed' : 'pointer';
    }
  }

  if (selectAll) {
    selectAll.addEventListener('change', function () {
      selectItems.forEach(item => item.checked = this.checked);
      updateSelectedCount();
    });
  }

  selectItems.forEach(item => {
    item.addEventListener('change', function () {
      if (selectAll) {
        selectAll.checked = [...selectItems].every(i => i.checked);
      }
      updateSelectedCount();
    });
  });

  updateSelectedCount(); // init

  // ── QTY +/- ────────────────────────────────────────────────────────
  function updateQtyOnServer(id, newQty) {
    document.getElementById('update-qty-' + id).value = newQty;
    fetch(`/chart-pinjam/update/${id}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-HTTP-Method-Override': 'PUT',
      },
      body: JSON.stringify({ qty: newQty, _method: 'PUT' })
    }).catch(err => console.error('Update qty error:', err));
  }

  document.querySelectorAll('.qty-minus').forEach(btn => {
    btn.addEventListener('click', function () {
      const id    = this.dataset.id;
      const min   = parseInt(this.dataset.min) || 1;
      const input = document.getElementById('qty-' + id);
      let val = parseInt(input.value);
      if (val > min) {
        val--;
        input.value = val;
        updateQtyOnServer(id, val);
        updateSelectedCount(); // recalc summary
      }
    });
  });

  document.querySelectorAll('.qty-plus').forEach(btn => {
    btn.addEventListener('click', function () {
      const id    = this.dataset.id;
      const max   = parseInt(this.dataset.max);
      const input = document.getElementById('qty-' + id);
      let val = parseInt(input.value);
      if (val < max) {
        val++;
        input.value = val;
        updateQtyOnServer(id, val);
        updateSelectedCount(); // recalc summary
      }
    });
  });

  // ── HAPUS ITEM ────────────────────────────────────────────────────
  document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', function () {
      const id   = this.dataset.id;
      const card = document.querySelector(`.cart-item[data-id="${id}"]`);
      if (!confirm('Hapus buku ini dari keranjang?')) return;

      fetch(`/chart-pinjam/remove/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          card.style.opacity   = '0';
          card.style.transform = 'translateX(20px)';
          setTimeout(() => {
            card.remove();
            updateSelectedCount(); // recalc setelah item dihapus

            if (document.querySelectorAll('.cart-item').length === 0) {
              window.location.reload();
            }
          }, 300);
        }
      })
      .catch(err => console.error('Remove error:', err));
    });
  });

});
</script>

</body>
</html>