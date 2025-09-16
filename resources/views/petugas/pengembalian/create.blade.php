@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Tambah Pengembalian
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.pengembalian.store') }}" method="POST">
                        @csrf

                        <!-- Peminjaman -->
                        <div class="mb-3">
                            <label for="peminjaman_id" class="form-label">Peminjaman</label>
                            <select name="peminjaman_id" class="form-control">
                                <option value="">-- Pilih Peminjaman --</option>
                                @foreach ($peminjamans as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->user->name }} - {{ $p->buku->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peminjaman_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Kembali -->
                        <div class="mb-3">
                            <label for="tgl_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control">
                            @error('tgl_kembali')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kondisi Buku -->
                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi Buku</label>
                            <select name="kondisi" class="form-control">
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                            @error('kondisi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <a href="{{ route('petugas.pengembalian.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
