<!-- Overlay -->
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

      {{-- BODY --}}
      <div class="minicart__single__wrapper">
        @php
          $chartPinjam = \App\Models\ChartPinjam::with('buku')->where('user_id', auth()->id())->get();
        @endphp
        @forelse ($chartPinjam as $item)
          @if($item->buku)
          <div class="minicart__single">
            <div class="minicart__single__img">
              <a href="{{ route('buku.detail', $item->buku->id) }}">
                <img src="{{ asset('images/buku/'.$item->buku->gambar) }}" alt="{{ $item->buku->judul }}">
              </a>
              <div class="minicart__single__close">
                <form action="{{ route('chart.pinjam.remove', $item->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button title="Remove"><i class="fa fa-close"></i></button>
                </form>
              </div>
            </div>
            <div class="minicart__single__content">
              <h4><a href="{{ route('buku.detail', $item->buku->id) }}">{{ $item->buku->judul }}</a></h4>
              <span><i class="fa fa-clone" style="margin-right: 4px;"></i>Jumlah: {{ $item->qty }}</span>
            </div>
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

      {{-- FOOTER --}}
      @php
        $totalBuku = $chartPinjam->sum('qty');
      @endphp
      @if($chartPinjam->count() > 0)
      <div class="minicart__footer">
        <div class="minicart__subtotal">
          <span class="subtotal__title">Total Buku:</span>
          <span class="subtotal__amount">{{ $totalBuku }} buku</span>
        </div>
        <div class="minicart__button">
          <a href="{{ route('chart.pinjam.index') }}" class="default__button default__button--outline">
            <i class="fa fa-shopping-cart" style="margin-right: 6px;"></i>Lihat Keranjang
          </a>
          <a href="{{ route('chart.pinjam.checkout') }}" class="default__button default__button--primary">
            <i class="fa fa-check-circle" style="margin-right: 6px;"></i>Proses Pinjam
          </a>
        </div>
      </div>
      @endif

    </div>
  </div>
</section>
<link rel="stylesheet" href="{{ asset('assets/frontend/css/cartmini.css') }}">
<script>
document.addEventListener('DOMContentLoaded', function(){
  const miniCart = document.getElementById('miniCart');
  const overlay = document.getElementById('cartOverlay');
  const closeBtn = document.getElementById('closeCart');
  const openBtns = document.querySelectorAll('.open-cart'); // tombol buka mini cart

  openBtns.forEach(btn => btn.addEventListener('click', () => {
    miniCart.classList.add('show');
    overlay.classList.add('show');
  }));

  closeBtn.addEventListener('click', () => {
    miniCart.classList.remove('show');
    overlay.classList.remove('show');
  });

  overlay.addEventListener('click', () => {
    miniCart.classList.remove('show');
    overlay.classList.remove('show');
  });
});
</script>