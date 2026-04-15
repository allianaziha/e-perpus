{{-- Overlay --}}
<div id="cartOverlay" class="cart-overlay"></div>

<section id="miniCart" class="minicart">
  <div class="minicart__inner">
    <div class="minicart__wrapper">

      {{-- HEADER --}}
      <div class="minicart__close__icon">
        <div class="minicart__cart__text">
          <i class="fa fa-book" style="margin-right: 8px; color: #1976d2;"></i>
          <strong>Keranjang Peminjaman</strong>
        </div>
        <button id="closeCart" class="minicart__close__btn">
          <i class="fa fa-close"></i>
        </button>
      </div>

      {{-- BODY: seluruh area ini yang di-refresh AJAX --}}
      <div id="miniCartBody">
        @auth
          @php
            $chartPinjam = \App\Models\ChartPinjam::with('buku')->where('user_id', auth()->id())->get();
            $totalBuku   = $chartPinjam->sum('qty');
          @endphp

          {{-- ── ITEMS ── --}}
          <div class="minicart__single__wrapper" id="miniCartItems">
            @forelse ($chartPinjam as $item)
              @if($item->buku)
                <div class="minicart__single">
                  <div class="minicart__single__checkbox">
                    <input type="checkbox" class="mini-cart-checkbox"
                           value="{{ $item->id }}"
                           data-qty="{{ $item->qty }}"
                           checked>
                  </div>
                  <div class="minicart__single__img">
                    <a href="{{ route('buku.detail', $item->buku->id) }}">
                      <img src="{{ asset('images/buku/'.$item->buku->gambar) }}" alt="{{ $item->buku->judul }}">
                    </a>
                  </div>
                  <div class="minicart__single__content">
                    <h4><a href="{{ route('buku.detail', $item->buku->id) }}">{{ $item->buku->judul }}</a></h4>
                    <span><i class="fa fa-clone" style="margin-right: 4px;"></i>Jumlah: {{ $item->qty }}</span>
                  </div>
                  <button class="remove-item-btn" data-item-id="{{ $item->id }}" title="Hapus item ini">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              @endif
            @empty
              <div class="empty-cart-state">
                <i class="fa fa-book" style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;"></i>
                <p>Keranjang kosong</p>
                <small>Tambahkan buku untuk dipinjam</small>
              </div>
            @endforelse
          </div>

          {{-- ── FOOTER ── --}}
          @if($chartPinjam->count() > 0)
            <div class="minicart__footer" id="miniCartFooter">
              <div class="minicart__subtotal">
                <span class="subtotal__title">Total Buku:</span>
                <span class="subtotal__amount" id="miniCartTotalBuku">{{ $totalBuku }} buku</span>
              </div>
              <div class="minicart__button">
                <a href="{{ route('chart.pinjam.index') }}" class="default__button default__button--outline">
                  <i class="fa fa-shopping-cart" style="margin-right: 6px;"></i>Lihat Keranjang
                </a>
                <a href="#" id="miniCartProsesBtn" class="default__button default__button--primary">
                  <i class="fa fa-check-circle" style="margin-right: 6px;"></i>Proses Pinjam
                </a>
              </div>
            </div>
          @endif

        @else
          {{-- Guest --}}
          <div class="empty-cart-state">
            <i class="fa fa-book" style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;"></i>
            <p>Silakan login dulu untuk melihat keranjang</p>
            <a href="{{ route('login') }}" class="default__button default__button--primary">Login</a>
          </div>
        @endauth
      </div>

    </div>
  </div>
</section>

<link rel="stylesheet" href="{{ asset('assets/frontend/css/cartmini.css') }}">

<script>
document.addEventListener('DOMContentLoaded', function () {
  const miniCart = document.getElementById('miniCart');
  const overlay  = document.getElementById('cartOverlay');
  const closeBtn = document.getElementById('closeCart');
  const openBtns = document.querySelectorAll('.open-cart');

  // ── Refresh SELURUH body via AJAX ────────────────────────────────────
  function refreshMiniCart() {
    fetch('/chart-pinjam/mini', {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html',
      },
    })
    .then(res => res.text())
    .then(html => {
      document.getElementById('miniCartBody').innerHTML = html;
      attachEventListeners();
    })
    .catch(err => console.error('Error refreshing mini cart:', err));
  }

  // ── Hitung ulang total & update tombol proses ────────────────────────
  function recalcMiniCart() {
  const checkboxes  = document.querySelectorAll('.mini-cart-checkbox');
  const checked     = document.querySelectorAll('.mini-cart-checkbox:checked');

  let totalQty      = 0;
  const selectedIds = [];
  checked.forEach(cb => {
    totalQty += parseInt(cb.dataset.qty) || 0;
    selectedIds.push(cb.value);
  });

  // Update label total buku
  const totalEl = document.getElementById('miniCartTotalBuku');
  if (totalEl) totalEl.textContent = totalQty + ' buku';

  // Update tombol proses pinjam
  const prosesBtn = document.getElementById('miniCartProsesBtn');
  if (!prosesBtn) return;

  // Reset onclick dulu
  prosesBtn.onclick = null;

  if (selectedIds.length === 0) {
    // Tidak ada yang dipilih — disable
    prosesBtn.setAttribute('href', '#');
    prosesBtn.style.opacity       = '0.5';
    prosesBtn.style.pointerEvents = 'none';
    prosesBtn.style.cursor        = 'not-allowed';

  } else {
    // Ada yang dipilih (sebagian atau semua) — selalu POST
    prosesBtn.setAttribute('href', '#');
    prosesBtn.style.opacity       = '1';
    prosesBtn.style.pointerEvents = 'auto';
    prosesBtn.style.cursor        = 'pointer';

    prosesBtn.onclick = function (e) {
      e.preventDefault();

      // Hapus form lama kalau ada
      const oldForm = document.getElementById('miniCartProsesForm');
      if (oldForm) oldForm.remove();

      const form  = document.createElement('form');
      form.id     = 'miniCartProsesForm';
      form.method = 'POST';
      form.action = '{{ route("user.peminjaman.store") }}';

      const csrf  = document.createElement('input');
      csrf.type   = 'hidden';
      csrf.name   = '_token';
      csrf.value  = document.querySelector('meta[name="csrf-token"]').content;
      form.appendChild(csrf);

      selectedIds.forEach(id => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'selected_items[]';
        inp.value = id;
        form.appendChild(inp);
      });

      document.body.appendChild(form);
      form.submit();
    };
  }
}

  // ── Pasang semua event listener ke elemen dinamis ───────────────────
  function attachEventListeners() {
    // Hapus per item
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        removeItem(this.getAttribute('data-item-id'));
      });
    });

    // Checkbox change
    document.querySelectorAll('.mini-cart-checkbox').forEach(cb => {
      cb.addEventListener('change', recalcMiniCart);
    });

    // Hitung saat pertama attach
    recalcMiniCart();
  }

  // ── Hapus satu item ──────────────────────────────────────────────────
  function removeItem(itemId) {
    fetch(`/chart-pinjam/remove/${itemId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        refreshMiniCart();
        updateCartCount(data.total_items);
      } else {
        alert('Gagal menghapus item.');
      }
    })
    .catch(err => console.error('Error removing item:', err));
  }

  // ── Update badge jumlah di navbar ────────────────────────────────────
  function updateCartCount(count) {
    document.querySelectorAll('.cart-count').forEach(el => {
      el.textContent = count;
    });
  }

  // ── Buka minicart ────────────────────────────────────────────────────
  openBtns.forEach(btn => btn.addEventListener('click', () => {
    miniCart.classList.add('show');
    overlay.classList.add('show');
    refreshMiniCart();
  }));

  // ── Tutup minicart ───────────────────────────────────────────────────
  closeBtn.addEventListener('click', () => {
    miniCart.classList.remove('show');
    overlay.classList.remove('show');
  });

  overlay.addEventListener('click', () => {
    miniCart.classList.remove('show');
    overlay.classList.remove('show');
  });

  // Pasang listener untuk elemen yang sudah ada saat halaman pertama load
  attachEventListeners();
});
</script>