@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Detail Pengembalian
                </div>
                <div class="card-body">
                    
                    <p><strong>ID :</strong> {{ $pengembalian->id }}</p>
                    <p><strong>Nama Peminjam :</strong> {{ $pengembalian->peminjaman->user->name ?? '-' }}</p>
                    <p><strong>Buku :</strong> {{ $pengembalian->peminjaman->buku->judul ?? '-' }}</p>
                    <p><strong>Tanggal Kembali :</strong> {{ $pengembalian->tgl_kembali }}</p>
                    <p>
                        <strong>Kondisi :</strong>  
                        @if($pengembalian->kondisi == 'baik')
                            <span class="badge bg-success">Baik</span>
                        @elseif($pengembalian->kondisi == 'rusak')
                            <span class="badge bg-warning text-dark">Rusak</span>
                        @else
                            <span class="badge bg-danger">Hilang</span>
                        @endif
                    </p>

                    <p><strong>Total Denda :</strong> Rp {{ number_format($pengembalian->denda->sum('nominal')) }}</p>

                </div>
                <div class="card-footer">
                    <a href="{{ route('pengembalian.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
