@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <h5>Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    
                    <p><strong>ID :</strong> {{ $peminjaman->id }}</p>
                    <p><strong>Nama Peminjam :</strong> {{ $peminjaman->user->name ?? '-' }}</p>
                    <p><strong>Buku :</strong> {{ $peminjaman->buku->judul ?? '-' }}</p>
                    <p><strong>Jumlah :</strong> {{ $peminjaman->jumlah_buuku ?? '-' }}</p>
                    <p><strong>Tanggal Pinjam :</strong> {{ $peminjaman->tgl_pinjam }}</p>
                    <p><strong>Tanggal Jatuh Tempo :</strong> {{ $peminjaman->tgl_jatuh_tempo }}</p>
                    <p>
                        <strong>Status :</strong>  
                        @if($peminjaman->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($peminjaman->status == 'dipinjam')
                            <span class="badge bg-secondary">Dipinjam</span>
                        @elseif($peminjaman->status == 'dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>
                        @elseif($peminjaman->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </p>

                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    <a href="{{ route('admin.peminjaman.edit', $peminjaman->id) }}" class="btn btn-sm btn-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
