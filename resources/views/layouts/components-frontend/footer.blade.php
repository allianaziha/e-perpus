<footer id="footer" class="footer py-4" style="background:#d9e6f2; color:#1a1a1a;">
  <div class="container">
    <div class="row gy-3">

      <div class="col-lg-4 col-md-6">
        <h4 class="sitename">E-Perpus</h4>
        <p>Perpustakaan Digital Sekolah untuk memudahkan pencarian, peminjaman, dan pengembalian buku.</p>
      </div>

      <div class="col-lg-2 col-md-3">
        <h5>Menu</h5>
        <ul class="list-unstyled">
          <li><a href="{{ url('/') }}" style="color:#1a1a1a; text-decoration:none;">Beranda</a></li>
          <li><a href="{{ url('/buku') }}" style="color:#1a1a1a; text-decoration:none;">Buku</a></li>
          <li><a href="{{ url('/peminjaman') }}" style="color:#1a1a1a; text-decoration:none;">Peminjaman</a></li>
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
          <a href="#" style="color:#1a1a1a;"><i class="bi bi-instagram"></i></a>
          <a href="#" style="color:#1a1a1a;"><i class="bi bi-facebook"></i></a>
          <a href="#" style="color:#1a1a1a;"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

    </div>

    <div class="text-center mt-3">
      <small>© {{ date('Y') }} E-Perpus — All Rights Reserved</small>
    </div>
  </div>
</footer>
