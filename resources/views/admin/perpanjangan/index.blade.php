@extends('layouts.backend')

@section ('title', 'Admin perpus - Perpanjangan Peminjaman')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataPerpanjangan th, #dataPerpanjangan td {
        vertical-align: middle;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
    .status-badge {
        font-size: 0.8em;
        padding: 0.25em 0.5em;
    }
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-approved { background-color: #28a745; color: #fff; }
    .badge-rejected { background-color: #dc3545; color: #fff; }
    .action-buttons .btn {
        margin: 0.125rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Judul Besar --}}
    <h3 class="mb-3 fw-bold text-uppercase">PERPANJANGAN PEMINJAMAN</h3>

    {{-- Alert untuk notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="ti ti-clock-pause display-4 text-warning"></i>
                    <h4 class="mt-2">{{ $stats['pending'] }}</h4>
                    <p class="text-muted mb-0">Menunggu Persetujuan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="ti ti-check-circle display-4 text-success"></i>
                    <h4 class="mt-2">{{ $stats['approved'] }}</h4>
                    <p class="text-muted mb-0">Disetujui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="ti ti-x-circle display-4 text-danger"></i>
                    <h4 class="mt-2">{{ $stats['rejected'] }}</h4>
                    <p class="text-muted mb-0">Ditolak</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="ti ti-file-text display-4 text-info"></i>
                    <h4 class="mt-2">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0">Total Request</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Status --}}
    <div class="row mb-3">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Filter Status:</h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.perpanjangan.index') }}"
                               class="btn btn-outline-primary btn-sm {{ request('status') == null ? 'active' : '' }}">
                                Semua
                            </a>
                            <a href="{{ route('admin.perpanjangan.index', ['status' => 'pending']) }}"
                               class="btn btn-outline-warning btn-sm {{ request('status') == 'pending' ? 'active' : '' }}">
                                Menunggu
                            </a>
                            <a href="{{ route('admin.perpanjangan.index', ['status' => 'approved']) }}"
                               class="btn btn-outline-success btn-sm {{ request('status') == 'approved' ? 'active' : '' }}">
                                Disetujui
                            </a>
                            <a href="{{ route('admin.perpanjangan.index', ['status' => 'rejected']) }}"
                               class="btn btn-outline-danger btn-sm {{ request('status') == 'rejected' ? 'active' : '' }}">
                                Ditolak
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Request Perpanjangan --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="ti ti-file-text text-primary"></i> Request Perpanjangan Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    @if($perpanjangans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0" id="dataPerpanjangan">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th class="text-center">User</th>
                                        <th class="text-center">Judul Buku</th>
                                        <th class="text-center">Lama Perpanjangan</th>
                                        <th class="text-center">Alasan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tanggal Request</th>
                                        <th class="text-center">Diproses Oleh</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($perpanjangans as $index => $perpanjangan)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $perpanjangan->peminjaman->user->name }}</strong><br>
                                                <small class="text-muted">{{ $perpanjangan->peminjaman->user->email }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $perpanjangan->peminjaman->buku->judul }}</strong><br>
                                                <small class="text-muted">{{ $perpanjangan->peminjaman->buku->pengarang }}</small>
                                            </td>
                                            <td class="text-center">{{ $perpanjangan->lama_perpanjangan }} hari</td>
                                            <td>
                                                <span title="{{ $perpanjangan->alasan }}">
                                                    {{ Str::limit($perpanjangan->alasan, 50) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($perpanjangan->status == 'pending')
                                                    <span class="badge badge-pending">Menunggu</span>
                                                @elseif($perpanjangan->status == 'approved')
                                                    <span class="badge badge-approved">Disetujui</span>
                                                @else
                                                    <span class="badge badge-rejected">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($perpanjangan->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                @if($perpanjangan->approvedBy)
                                                    {{ $perpanjangan->approvedBy->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center action-buttons">
                                                <a href="{{ route('admin.perpanjangan.show', $perpanjangan) }}"
                                                   class="btn btn-info btn-sm">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                @if($perpanjangan->status == 'pending')
                                                    <form action="{{ route('admin.perpanjangan.approve', $perpanjangan) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui request perpanjangan ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.perpanjangan.reject', $perpanjangan) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menolak request perpanjangan ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Tidak ada request perpanjangan</h5>
                            <p class="text-muted">
                                @if(request('status'))
                                    Tidak ada request dengan status yang dipilih.
                                @else
                                    Belum ada request perpanjangan yang diajukan.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        $('#dataPerpanjangan').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "ordering": true,
            "responsive": true,
            "order": [[6, 'desc']] // Urutkan berdasarkan tanggal request terbaru
        });
    });
</script>
@endsection