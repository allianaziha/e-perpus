@extends('layouts.backend')

@section ('title', 'Admin perpus - Detail Perpanjangan')

@section('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
    .status-card {
        border-left: 4px solid;
    }
    .status-pending { border-left-color: #ffc107; }
    .status-approved { border-left-color: #28a745; }
    .status-rejected { border-left-color: #dc3545; }
    .info-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .action-buttons .btn {
        margin: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Judul Besar --}}
    <h3 class="mb-3 fw-bold text-uppercase">DETAIL REQUEST PERPANJANGAN</h3>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.perpanjangan.index') }}">Perpanjangan</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail Request</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            {{-- Status Card --}}
            <div class="card shadow status-card
                @if($perpanjangan->status == 'pending') status-pending
                @elseif($perpanjangan->status == 'approved') status-approved
                @else status-rejected @endif">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text text-primary"></i> Status Request Perpanjangan
                        </h5>
                        @if($perpanjangan->status == 'pending')
                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                        @elseif($perpanjangan->status == 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($perpanjangan->status == 'approved')
                        <div class="alert alert-success">
                            <i class="ti ti-check-circle"></i>
                            <strong>Request telah disetujui</strong> pada {{ \Carbon\Carbon::parse($perpanjangan->approved_at)->format('d/m/Y H:i') }}
                        </div>
                    @elseif($perpanjangan->status == 'rejected')
                        <div class="alert alert-danger">
                            <i class="ti ti-x-circle"></i>
                            <strong>Request telah ditolak</strong> pada {{ \Carbon\Carbon::parse($perpanjangan->approved_at)->format('d/m/Y H:i') }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="ti ti-clock-pause"></i>
                            <strong>Menunggu Persetujuan</strong> - Request ini belum diproses.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Request --}}
            <div class="card shadow mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ti ti-info-circle text-info"></i> Informasi Request
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Tanggal Request</h6>
                                <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($perpanjangan->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Lama Perpanjangan</h6>
                                <p class="mb-0 fw-bold">{{ $perpanjangan->lama_perpanjangan }} hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h6 class="text-muted mb-2">Alasan Perpanjangan</h6>
                        <p class="mb-0">{{ $perpanjangan->alasan }}</p>
                    </div>

                    @if($perpanjangan->approved_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h6 class="text-muted mb-2">Diproses Oleh</h6>
                                    <p class="mb-0 fw-bold">{{ $perpanjangan->approvedBy->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h6 class="text-muted mb-2">Tanggal Diproses</h6>
                                    <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($perpanjangan->approved_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informasi User --}}
            <div class="card shadow mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ti ti-user text-success"></i> Informasi User
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Nama User</h6>
                                <p class="mb-0 fw-bold">{{ $perpanjangan->peminjaman->user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Email</h6>
                                <p class="mb-0 fw-bold">{{ $perpanjangan->peminjaman->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Buku dan Peminjaman --}}
            <div class="card shadow mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ti ti-book text-primary"></i> Informasi Buku & Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Judul Buku</h6>
                                <p class="mb-0 fw-bold">{{ $perpanjangan->peminjaman->buku->judul }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Pengarang</h6>
                                <p class="mb-0 fw-bold">{{ $perpanjangan->peminjaman->buku->pengarang }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Tanggal Pinjam</h6>
                                <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($perpanjangan->peminjaman->tgl_pinjam)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="text-muted mb-2">Jatuh Tempo Saat Ini</h6>
                                <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($perpanjangan->peminjaman->tgl_jatuh_tempo)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($perpanjangan->status == 'approved')
                        <div class="info-section">
                            <h6 class="text-muted mb-2">Jatuh Tempo Setelah Perpanjangan</h6>
                            <p class="mb-0 fw-bold text-success">
                                {{ \Carbon\Carbon::parse($perpanjangan->peminjaman->tgl_jatuh_tempo)->addDays($perpanjangan->lama_perpanjangan)->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif

                    <div class="info-section">
                        <h6 class="text-muted mb-2">Jumlah Pinjam</h6>
                        <p class="mb-0 fw-bold">{{ $perpanjangan->peminjaman->jumlah_buku }} buku</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="card shadow mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.perpanjangan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>

                        @if($perpanjangan->status == 'pending')
                            <div class="action-buttons">
                                <form action="{{ route('admin.perpanjangan.approve', $perpanjangan) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menyetujui request perpanjangan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="ti ti-check"></i> Setujui Perpanjangan
                                    </button>
                                </form>
                                <form action="{{ route('admin.perpanjangan.reject', $perpanjangan) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menolak request perpanjangan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="ti ti-x"></i> Tolak Perpanjangan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection