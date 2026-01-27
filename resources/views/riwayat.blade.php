@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h3 class="text-center mb-4">📚 Riwayat Peminjaman Buku</h3>

            @if($riwayat->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada riwayat peminjaman buku.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
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
                                <td>{{ $item->buku->judul ?? 'Buku tidak ditemukan' }}</td>
                                <td>{{ $item->jumlah_buku }} buku</td>
                                <td>{{ $item->tgl_pinjam->format('d M Y') }}</td>
                                <td>{{ $item->tgl_jatuh_tempo->format('d M Y') }}</td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($item->status == 'rejected')
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