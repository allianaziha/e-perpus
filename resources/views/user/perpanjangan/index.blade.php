@extends('layouts.backend')

@section ('title', ' perpus - Perpanjangan Peminjaman')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataPeminjaman th, #dataPeminjaman td,
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
    /* Fix layout issues for user pages */
    .body-wrapper {
        padding-top: 20px !important;
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

    {{-- Card Peminjaman yang Bisa Diperpanjang --}}
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-book text-primary"></i> Peminjaman Aktif
                    </h5>
                </div>
                <div class="card-body">
                    @if($peminjamans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0" id="dataPeminjaman">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th class="text-center">Judul Buku</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Tgl Pinjam</th>
                                        <th class="text-center">Tgl Jatuh Tempo</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamans as $index => $peminjaman)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $peminjaman->buku->judul }}</strong><br>
                                                <small class="text-muted">{{ $peminjaman->buku->pengarang }}</small>
                                            </td>
                                            <td class="text-center">{{ $peminjaman->jumlah_buku }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->format('d/m/Y') }}
                                                @if(\Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->isPast())
                                                    <br><small class="text-danger">Terlambat</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($peminjaman->perpanjanganRequests()->where('status', 'pending')->exists())
                                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                                @else
                                                    <span class="badge bg-success">Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$peminjaman->perpanjanganRequests()->where('status', 'pending')->exists())
                                                    <a href="{{ route('user.perpanjangan.create', $peminjaman) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="ti ti-clock-plus"></i> Ajukan Perpanjangan
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="ti ti-clock-pause"></i> Menunggu
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-book-off display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Tidak ada peminjaman aktif</h5>
                            <p class="text-muted">Anda belum memiliki peminjaman yang bisa diperpanjang.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Card Riwayat Request Perpanjangan --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-history text-info"></i> Riwayat Request Perpanjangan
                    </h5>
                </div>
                <div class="card-body">
                    @if($perpanjanganRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0" id="dataPerpanjangan">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th class="text-center">Judul Buku</th>
                                        <th class="text-center">Lama Perpanjangan</th>
                                        <th class="text-center">Alasan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tanggal Request</th>
                                        <th class="text-center">Diproses Oleh</th>
                                        <th class="text-center" style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($perpanjanganRequests as $index => $request)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $request->peminjaman->buku->judul }}</strong><br>
                                                <small class="text-muted">{{ $request->peminjaman->buku->pengarang }}</small>
                                            </td>
                                            <td class="text-center">{{ $request->lama_perpanjangan }} hari</td>
                                            <td>
                                                <span title="{{ $request->alasan }}">
                                                    {{ Str::limit($request->alasan, 50) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($request->status == 'pending')
                                                    <span class="badge badge-pending">Menunggu</span>
                                                @elseif($request->status == 'approved')
                                                    <span class="badge badge-approved">Disetujui</span>
                                                @else
                                                    <span class="badge badge-rejected">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                @if($request->approvedBy)
                                                    {{ $request->approvedBy->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('user.perpanjangan.show', $request) }}"
                                                   class="btn btn-info btn-sm">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Belum ada request perpanjangan</h5>
                            <p class="text-muted">Riwayat request perpanjangan akan muncul di sini.</p>
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
        $('#dataPeminjaman').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "ordering": true,
            "responsive": true
        });

        $('#dataPerpanjangan').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "ordering": true,
            "responsive": true,
            "order": [[5, 'desc']] // Urutkan berdasarkan tanggal request terbaru
        });
    });
</script>
@endsection