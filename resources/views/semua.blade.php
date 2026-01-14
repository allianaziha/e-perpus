<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>
    @if(isset($selectedCategory))
        Buku Kategori {{ $selectedCategory->nama }} - e-Perpus
    @else
        Semua Buku - e-Perpus
    @endif
   </title>

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

  <style>
    /* agar konten tidak naik ke navbar */
    body.index-page {
        padding-top: 100px;
    }

    /* Sidebar */
    .sidebar {
    padding: 15px;
    background: #f9f9f9;
    border-radius: 10px;
    margin-top: 80px; /* atur jaraknya di sini */
    }

    /* Gambar Buku */
    .book-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s;
    }

    .book-img:hover {
        transform: scale(1.05);
    }

    .book-img-popular {
        width: 100%;
        height: 380px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s;
    }

    .book-img-popular:hover {
        transform: scale(1.05);
    }

    /* Grid Buku */
    .grid__responsive {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Qty Input */
    #qtyInput::-webkit-outer-spin-button,
    #qtyInput::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #qtyInput {
        -moz-appearance: textfield;
    }
  </style>
</head>

<body class="index-page">

  {{-- Navbar --}}
  @include('layouts.components-frontend.navbar')

  <div class="shop sp_top_80">
      <div class="container">
          <div class="row">

              <!-- Sidebar Kategori Buku -->
              <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
                  <div class="sidebar sidebar-collapse-hide">
                      <div class="sidebar__widget widget-collapse-show">
                          <div class="sidebar__title">
                              <h4>Kategori Buku</h4>
                              <i class="fa fa-angle-down"></i>
                          </div>

                          <div class="sidebar__menu">
                              <ul>
                                  <li>
                                      <a href="{{ route('buku.index') }}"
                                      class="{{ !isset($selectedCategory) ? 'fw-bold text-primary' : '' }}">
                                          Semua Buku
                                      </a>
                                  </li>

                                  @foreach ($kategori as $cat)
                                      <li>
                                          <a href="{{ route('buku.filter', $cat->id) }}"
                                          class="{{ isset($selectedCategory) && $selectedCategory->id == $cat->id ? 'fw-bold text-primary' : '' }}">
                                              {{ $cat->nama }}
                                              <span>({{ $cat->bukus->count() }})</span>
                                          </a>
                                      </li>
                                  @endforeach
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Daftar Buku -->
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                <div class="tab-content mt-5">
                    <div class="tab-pane fade active show" id="books__one" role="tabpanel">
                        <div class="row g-4 justify-content-start">
                            @if ($buku->isEmpty())
                                <p class="text-muted">Tidak ada buku.</p>
                            @else
                                @foreach ($buku as $data)
                                    <div class="col-md-4 d-flex justify-content-center">
                                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="width: 100%; max-width: 250px; height: 380px;">
                                            <a href="{{ route('buku.detail', $data->id) }}" class="text-decoration-none d-block h-100">
                                                <img src="{{ asset('images/buku/' . $data->gambar) }}"
                                                    alt="{{ $data->judul }}"
                                                    style="width:100%; height:100%; object-fit:cover;">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                  <!-- Pagination -->
                  <div class="mt-4">
                      {{ $buku->links() }}
                  </div>
              </div>

          </div>
      </div>
  </div>

  {{-- Footer --}}
  @include('layouts.components-frontend.footer')

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Vendor JS -->
  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/js/main.js') }}"></script>

</body>
</html>
