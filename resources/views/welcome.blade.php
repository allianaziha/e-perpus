<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>e-perpus</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link rel="shortcut icon" type="image/png" href="{{asset('assets/backend/images/logos/logo-mini.png')}}" />
  <link href="{{ asset('assets/frontend/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

      @include('layouts.components-frontend.navbar')

  <main class="main">
  
    @include('layouts.components-frontend.home')

    @include('layouts.components-frontend.about')

    @include('layouts.components-frontend.main')

    <!-- Services Section -->
    <!-- Features / Layanan -->
    <section id="services" class="services section light-background">
      <div class="container section-title text-center">
        <h2>Fitur Utama</h2>
        <p>Fitur unggulan dalam sistem e-Perpus</p>
      </div>

      <div class="container">
        <div class="row g-4">

          <div class="col-lg-4">
            <div class="service-card text-center p-4">
              <i class="bi bi-book fs-1"></i>
              <h3>Peminjaman Buku</h3>
              <p>Ajukan peminjaman buku secara online dengan mudah.</p>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="service-card text-center p-4">
              <i class="bi bi-arrow-repeat fs-1"></i>
              <h3>Perpanjangan</h3>
              <p>Perpanjang masa pinjam tanpa harus datang ke perpustakaan.</p>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="service-card text-center p-4">
              <i class="bi bi-cash-stack fs-1"></i>
              <h3>Denda Otomatis</h3>
              <p>Denda dihitung otomatis jika terlambat.</p>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="service-card text-center p-4">
              <i class="bi bi-person-check fs-1"></i>
              <h3>ACC Admin</h3>
              <p>Peminjaman hanya aktif setelah disetujui admin.</p>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="service-card text-center p-4">
              <i class="bi bi-clock-history fs-1"></i>
              <h3>Riwayat</h3>
              <p>Lihat semua riwayat peminjaman dan pengembalian.</p>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="service-card text-center p-4">
              <i class="bi bi-file-earmark-text fs-1"></i>
              <h3>Laporan</h3>
              <p>Cetak laporan peminjaman dalam PDF/Excel.</p>
            </div>
          </div>

        </div>
      </div>
    </section>

<!-- FAQ (GANTI PRICING) -->
    <section id="pricing" class="pricing section light-background">
      <div class="container section-title text-center">
        <h2>FAQ</h2>
        <p>Pertanyaan yang sering ditanyakan</p>
      </div>

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div class="accordion" id="faqAccordion">

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faq1">
                    Cara meminjam buku?
                  </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show">
                  <div class="accordion-body">
                    Login → pilih buku → klik pinjam → tunggu ACC admin.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                    Kapan tanggal pinjam dihitung?
                  </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Dihitung saat admin menyetujui (ACC).
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                    Bisa perpanjang?
                  </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Bisa sebelum tanggal pengembalian.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">
                    Kalau telat?
                  </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Akan dikenakan denda otomatis oleh sistem.
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </section>

  </main>

  @include('layouts.components-frontend.footer')


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/frontend/vendor/purecounter/purecounter_vanilla.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/frontend/js/main.js') }}"></script>
  @include('sweetalert::alert')
  @yield('js')
  @stack('scripts')

</body>

</html>
