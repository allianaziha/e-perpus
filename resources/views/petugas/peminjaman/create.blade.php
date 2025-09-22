@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Tambah Peminjaman
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.peminjaman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="from_petugas" value="true">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Nama User</label>
                                    <select name="user_id" class="form-control" >
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="buku_id" class="form-label">Judul Buku</label>
                                    <select name="buku_id" class="form-control" >
                                        <option value="">-- Pilih Buku --</option>
                                        @foreach ($bukus as $b)
                                            <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                                                {{ $b->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('buku_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_buku" class="form-label">Jumlah Buku</label>
                                    <input type="number" name="jumlah_buku" class="form-control" min="1" value="{{ old('jumlah_buku') }}">
                                    @error('jumlah_buku') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                                    <input type="date" name="tgl_pinjam" class="form-control" value="{{ old('tgl_pinjam') }}">
                                    @error('tgl_pinjam') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
