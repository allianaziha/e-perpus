<section id="hero" class="hero section">
  <div class="container-fluid px-5" data-aos="fade-up" data-aos-delay="100">
    <!-- Carousel -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        @foreach($banners as $key => $banner)
          <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
            <div class="row align-items-center justify-content-between px-lg-5 px-md-4 px-3" style="min-height: 350px;">
              
              <!-- Text -->
              <div class="col-lg-6 d-flex flex-column justify-content-center ps-lg-5">
                <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                  <h1 class="fw-bold mb-3" style="font-size: 3rem; line-height: 1.2; color: #1d3557;">
                    {{ $banner->judul_utama }}
                  </h1>
                  <h2 class="fw-semibold mb-3 text-primary" style="font-size: 2.5rem;">
                    {{ $banner->judul }}
                  </h2>
                  <p class="mb-4 text-secondary" style="font-size: 1.1rem;">
                    {{ $banner->deskripsi }}
                  </p>
                </div>
              </div>

              <!-- Gambar -->
              <div class="col-lg-6 d-flex justify-content-center align-items-center pe-lg-5">
                <img src="{{ asset('images/banner/' . $banner->gambar) }}" class="img-fluid" style="max-height: 420px;">
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Controls -->
      <button 
        class="carousel-control-prev d-flex align-items-center justify-content-center" 
        type="button" 
        data-bs-target="#heroCarousel" 
        data-bs-slide="prev"
        style="width: 4%; top: 50%; transform: translateY(-50%); left: 0; opacity: 0.9;">
        <span class="carousel-control-prev-icon" aria-hidden="true" 
              style="background-color: rgba(0,0,0,0.5); border-radius: 50%; background-size: 60%, 60%;"></span>
        <span class="visually-hidden">Previous</span>
      </button>

      <button 
        class="carousel-control-next d-flex align-items-center justify-content-center" 
        type="button" 
        data-bs-target="#heroCarousel" 
        data-bs-slide="next"
        style="width: 4%; top: 50%; transform: translateY(-50%); right: 0; opacity: 0.9;">
        <span class="carousel-control-next-icon" aria-hidden="true" 
              style="background-color: rgba(0,0,0,0.5); border-radius: 50%; background-size: 60%, 60%;"></span>
        <span class="visually-hidden">Next</span>
      </button>

      <!-- Indicators -->
      <div class="carousel-indicators mt-3">
        @foreach($banners as $key => $banner)
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" 
                  class="{{ $key == 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $key + 1 }}"></button>
        @endforeach
      </div>
    </div>
    <!-- Statistik -->
    <div class="stats-container bg-white rounded-4 shadow-sm p-4 mt-5" data-aos="fade-up" data-aos-delay="500">
      <div class="row text-center justify-content-center align-items-center">
        <!-- User Aktif -->
        <div class="col-6 col-md-4 mb-4 mb-md-0">
          <div class="d-flex flex-column align-items-center">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
              <i class="bi bi-people fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: #1d3557;">{{ $userCount }}</h5>
            <p class="text-secondary mb-0">User Aktif</p>
          </div>
        </div>

        <!-- Total Buku -->
        <div class="col-6 col-md-4 mb-4 mb-md-0">
          <div class="d-flex flex-column align-items-center">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
              <i class="bi bi-book fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: #1d3557;">{{ $bukuCount }}</h5>
            <p class="text-secondary mb-0">Total Buku</p>
          </div>
        </div>

        <!-- Total Peminjaman -->
        <div class="col-6 col-md-4">
          <div class="d-flex flex-column align-items-center">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
              <i class="bi bi-arrow-left-right fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: #1d3557;">{{ $pinjamCount }}</h5>
            <p class="text-secondary mb-0">Total Peminjaman</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
