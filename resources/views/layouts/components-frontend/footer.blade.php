<footer id="footer" class="footer py-4">
  <div class="container">
    <div class="row gy-3">

      <div class="col-lg-4 col-md-6">
        <h4 class="sitename">E-Perpus</h4>
        <p>Perpustakaan Digital Sekolah untuk memudahkan pencarian, peminjaman, dan pengembalian buku.</p>
      </div>

      <div class="col-lg-2 col-md-3">
        <h5>Menu</h5>
        <ul class="list-unstyled">
          <li><a href="{{ url('/') }}">Beranda</a></li>
          <li><a href="{{ url('/buku') }}">Buku</a></li>
          <li><a href="{{ url('/peminjaman') }}">Peminjaman</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-3">
        <h5>Kontak</h5>
        <p>Email: perpus@sekolah.ac.id<br>
        Telp: +62 812-3456-7890</p>
      </div>

      <div class="col-lg-3 col-md-12">
        <h5>Sosial Media</h5>
        <div class="d-flex gap-3">
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

    </div>

    <div class="text-center mt-3">
      <small>© {{ date('Y') }} E-Perpus — All Rights Reserved</small>
    </div>
  </div>
</footer>
