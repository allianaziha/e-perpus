@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-white">
                    <h5>Bayar Denda</h5>
                </div>
                <div class="card-body">
                    <p><strong>Peminjaman:</strong> 
                        {{ $denda->pengembalian->peminjaman->buku->judul ?? '-' }} 
                        ({{ $denda->pengembalian->peminjaman->user->name ?? '-' }})
                    </p>
                    <hr>

                    <p><strong>Total Denda:</strong> Rp {{ number_format($denda->nominal, 0, ',', '.') }}</p>
                    <hr>

                    <p><strong>Sudah Dibayar:</strong> Rp {{ number_format($denda->dibayar, 0, ',', '.') }}</p>
                    <hr>

                    <p><strong>Sisa Bayar:</strong> 
                        Rp {{ number_format($denda->nominal - $denda->dibayar, 0, ',', '.') }}
                    </p>
                    <hr>

                    <p><strong>Status:</strong> 
                        <span class="badge {{ $denda->status == 'belum lunas' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($denda->status) }}
                        </span>
                    </p>
                    <hr>

                    <div class="mt-3">
                        <a href="{{ route('petugas.denda.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('petugas.denda.edit', $denda->id) }}" class="btn btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
