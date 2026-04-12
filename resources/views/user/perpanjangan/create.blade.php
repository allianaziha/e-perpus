@extends('layouts.backend')

@section ('title', ' perpus - Ajukan Perpanjangan')

@section('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
    .book-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .form-floating > label {
        padding: 1rem 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Judul Besar --}}
    <h3 class="mb-3 fw-bold text-uppercase">AJUKAN PERPANJANGAN PEMINJAMAN</h3>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('user.perpanjangan.index') }}">Perpanjangan</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Ajukan Perpanjangan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ti ti-clock-plus text-primary"></i> Form Pengajuan Perpanjangan
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Info Buku --}}
                    <div class="book-info">
                        <h6 class="mb-3">
                            <i class="ti ti-book text-primary"></i> Informasi Peminjaman
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Judul Buku:</strong><br>
                                {{ $peminjaman->buku->judul }}
                            </div>
                            <div class="col-md-6">
                                <strong>Pengarang:</strong><br>
                                {{ $peminjaman->buku->pengarang }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Tanggal Pinjam:</strong><br>
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d/m/Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Jatuh Tempo:</strong><br>
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->format('d/m/Y') }}
                                @if(\Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->isPast())
                                    <span class="badge bg-danger ms-2">Terlambat</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Jumlah Pinjam:</strong><br>
                                {{ $peminjaman->jumlah_buku }} buku
                            </div>
                        </div>
                    </div>

                    {{-- Form Pengajuan --}}
                    <form action="{{ route('user.perpanjangan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select @error('lama_perpanjangan') is-invalid @enderror"
                                            id="lama_perpanjangan" name="lama_perpanjangan" required>
                                        <option value="">Pilih lama perpanjangan</option>
                                        <option value="7" {{ old('lama_perpanjangan') == '7' ? 'selected' : '' }}>7 hari</option>
                                        <option value="14" {{ old('lama_perpanjangan') == '14' ? 'selected' : '' }}>14 hari</option>
                                        <option value="21" {{ old('lama_perpanjangan') == '21' ? 'selected' : '' }}>21 hari</option>
                                        <option value="30" {{ old('lama_perpanjangan') == '30' ? 'selected' : '' }}>30 hari</option>
                                    </select>
                                    <label for="lama_perpanjangan">Lama Perpanjangan</label>
                                    @error('lama_perpanjangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('alasan') is-invalid @enderror"
                                      placeholder="Jelaskan alasan perpanjangan peminjaman"
                                      id="alasan" name="alasan" style="height: 120px" required>{{ old('alasan') }}</textarea>
                            <label for="alasan">Alasan Perpanjangan</label>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="ti ti-info-circle"></i>
                            <strong>Informasi:</strong> Request perpanjangan akan diproses oleh admin/petugas.
                            Anda akan menerima notifikasi setelah request disetujui atau ditolak.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.perpanjangan.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-send"></i> Ajukan Perpanjangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-resize textarea
        $('#alasan').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Trigger resize on page load if there's content
        if ($('#alasan').val()) {
            $('#alasan').trigger('input');
        }
    });
</script>
@endsection