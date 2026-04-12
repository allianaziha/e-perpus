@extends('layouts.backend')

@section ('title', 'Riwayat Peminjaman - Perpus')

@section('styles')
<style>
    /* Fix layout issues for user pages */
    .body-wrapper {
        padding-top: 20px !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-3 fw-bold text-uppercase">📚 Riwayat Peminjaman Buku</h3>
            <hr style="border: 0; border-top: 1px solid #e0e0e0; margin: 10px 0;">

            @if($riwayat->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="ti ti-info-circle fs-4 mb-2"></i>
                    <h5>Belum ada riwayat peminjaman</h5>
                    <p class="mb-0">Anda belum pernah meminjam buku di perpustakaan ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0" id="riwayatTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th class="text-center">Judul Buku</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Tgl Pinjam</th>
                                <th class="text-center">Tgl Jatuh Tempo</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayat as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->buku->judul ?? 'Buku tidak ditemukan' }}</td>
                                <td class="text-center">{{ $item->jumlah_buku }} buku</td>
                                <td class="text-center">{{ $item->tgl_pinjam ? \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') : '-' }}</td>
                                <td class="text-center">{{ $item->tgl_jatuh_tempo ? \Carbon\Carbon::parse($item->tgl_jatuh_tempo)->format('d M Y') : '-' }}</td>
                                <td class="text-center">
                                    @if($item->status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="badge bg-success">Sedang Dipinjam</span>
                                    @elseif($item->status == 'dikembalikan')
                                        <span class="badge bg-info">Sudah Dikembalikan</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<script>
    new DataTable('#riwayatTable');
</script>
@endpush