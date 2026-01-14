<section id="highlight-books" class="section">
  <div class="container section-title text-center">
    <h2>Perpustakaan</h2>
  </div>

  <div class="container">
    <!-- Tabs -->
    <div class="d-flex justify-content-center mb-4">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#popular-books">
            <h6>Buku Terpopuler</h6>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#latest-books">
            <h6>Buku Terbaru</h6>
          </a>
        </li>
      </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
      <!-- Buku Terpopuler -->
      <div class="tab-pane fade show active" id="popular-books">
        <div class="row g-4">
          @foreach($populer as $buku)
            <div class="col-md-3 d-flex justify-content-center">
              <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="width: 100%; max-width: 250px; height: 380px;">
                <a href="{{ route('buku.detail', $buku->id) }}" class="text-decoration-none d-block h-100">
                  <img src="{{ asset('images/buku/'.$buku->gambar) }}" 
                       alt="{{ $buku->judul }}" 
                       class="img-fluid w-100 h-100 object-fit-cover">
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Buku Terbaru -->
      <div class="tab-pane fade" id="latest-books">
        <div class="row g-4">
          @foreach($terbaru as $buku)
            <div class="col-md-3 d-flex justify-content-center">
              <div class="card shadow-sm border-0 rounded-4 overflow-hidden position-relative" style="width: 100%; max-width: 250px; height: 380px;">
                <a href="{{ route('buku.detail', $buku->id) }}" class="d-block h-100 text-decoration-none">
                  <img src="{{ asset('images/buku/'.$buku->gambar) }}" 
                       alt="{{ $buku->judul }}" 
                       class="img-fluid w-100 h-100 object-fit-cover">
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
       <div class="text-center mt-3">
      <a href="{{ route('buku.semua') }}" class="btn btn-primary px-4 py-2 rounded-3">
        Lihat Semua Buku
      </a>
    </div>
    </div>
  </div>
</section>
