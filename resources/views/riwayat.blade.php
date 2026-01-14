<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title> e-Perpus</title>

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
</head>

<body class="index-page">

  {{-- Navbar --}}
  @include('layouts.components-frontend.navbar')

  <main style="padding-top: 100px;">
  <div class="container my-5 p-4 bg-white rounded shadow-sm" >
    <h3 class="text-center mb-4">📚 Riwayat Peminjaman Buku</h3>

    @if($riwayat->isEmpty())
      <div class="alert alert-info text-center">
        Belum ada riwayat peminjaman buku.
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center mt-3">
          <thead class="table-primary">
            <tr>
              <th>No</th>
              <th>Judul Buku</th>
              <th>Jumlah</th>
              <th>Tanggal Pinjam</th>
              <th>Jatuh Tempo</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($riwayat as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td>{{ $item->jumlah_buku }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl_jatuh_tempo)->format('d M Y') }}</td>
                <td>
                    @if($item->status == 'pending')
                        <span class="badge bg-warning text-dark">Menunggu</span>
                    @elseif($item->status == 'disetujui')
                        <span class="badge bg-success">Dipinjam</span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="badge bg-primary">Dikembalikan</span>
                    @elseif($item->status == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                    @endif
                    </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
    </div>
    </main>

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

