@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4>Bayar Denda</h4>
                </div>
                <div class="card-body">
                    <p><strong>Peminjaman:</strong> 
                        {{ $denda->pengembalian->peminjaman->buku->judul ?? '-' }}
                        ({{ $denda->pengembalian->peminjaman->user->name ?? '-' }})
                    </p>
                    <p><strong>Total Denda:</strong> Rp {{ number_format($denda->nominal, 0, ',', '.') }}</p>
                    <p><strong>Sudah Dibayar:</strong> Rp {{ number_format($denda->dibayar, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge {{ $denda->status == 'belum lunas' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($denda->status) }}
                        </span>
                    </p>
                    <hr>

                    <form action="{{ route('admin.denda.update', $denda->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="dibayar" class="form-label">Jumlah Bayar Sekarang</label>
                            <input type="number" name="dibayar" id="dibayar" class="form-control" required min="1">
                            @error('dibayar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Bayar</button>
                            <a href="{{ route('admin.denda.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
