@extends('layouts.backend')

@section ('title', 'Perpustakaan - Buku Favorit')

@section('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
    .favorit-btn {
        transition: all 0.2s ease;
    }
    .favorit-btn:hover {
        transform: scale(1.1);
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Judul Besar --}}
    <h3 class="mb-3 fw-bold text-uppercase">
        <i class="ti ti-heart text-danger me-2"></i>BUKU FAVORIT SAYA
    </h3>

    @if($favoritBukus->count() > 0)
        <div class="row">
            @foreach($favoritBukus as $favorit)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            {{-- Gambar Buku --}}
                            <div class="text-center mb-3">
                                @if($favorit->buku->gambar)
                                    <img src="{{ asset('images/buku/'.$favorit->buku->gambar) }}"
                                         alt="{{ $favorit->buku->judul }}"
                                         class="img-fluid rounded"
                                         style="height: 200px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="ti ti-book text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Info Buku --}}
                            <h6 class="card-title fw-bold mb-2">{{ $favorit->buku->judul }}</h6>
                            <p class="text-muted small mb-1">
                                <i class="ti ti-user me-1"></i>{{ $favorit->buku->penulis }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="ti ti-building me-1"></i>{{ $favorit->buku->penerbit }}
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="ti ti-calendar me-1"></i>{{ $favorit->buku->tahun_terbit }}
                            </p>

                            {{-- Stok --}}
                            <div class="mb-3">
                                <span class="badge {{ $favorit->buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                    Stok: {{ $favorit->buku->stok }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('buku.detail', $favorit->buku->id) }}"
                                       class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="ti ti-eye me-1"></i>Lihat
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm favorit-btn"
                                            onclick="hapusFavorit({{ $favorit->buku->id }}, '{{ $favorit->buku->judul }}')">
                                        <i class="ti ti-heart-off me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-state">
            <i class="ti ti-heart-off"></i>
            <h4>Belum ada buku favorit</h4>
            <p>Klik ikon hati di halaman detail buku untuk menambahkan ke favorit.</p>
            <a href="{{ route('buku.index') }}" class="btn btn-primary">
                <i class="ti ti-book me-2"></i>Jelajahi Buku
            </a>
        </div>
    @endif
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="hapusFavoritModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus dari Favorit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong id="bukuJudul"></strong> dari daftar favorit?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="konfirmasiHapusBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let bukuIdToDelete = null;

function hapusFavorit(bukuId, judul) {
    bukuIdToDelete = bukuId;
    document.getElementById('bukuJudul').textContent = judul;
    new bootstrap.Modal(document.getElementById('hapusFavoritModal')).show();
}

document.getElementById('konfirmasiHapusBtn').addEventListener('click', function() {
    if (!bukuIdToDelete) return;

    fetch(`{{ url('favorit') }}/${bukuIdToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Tutup modal
            bootstrap.Modal.getInstance(document.getElementById('hapusFavoritModal')).hide();

            // Show success message with SweetAlert2
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menghapus favorit',
            icon: 'error'
        });
    });
});
</script>
@endsection