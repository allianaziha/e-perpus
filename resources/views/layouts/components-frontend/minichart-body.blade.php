{{-- =================================================================
     PARTIAL VIEW — hanya isi dalam #miniCartBody
     Di-render via AJAX oleh ChartPinjamController@mini
     resources/views/layouts/components-frontend/minicart-body.blade.php
     ================================================================= --}}

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