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
                    <form action="{{ route('admin.peminjaman.store') }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Nama User</label>
                                    <select name="user_id" class="form-control">
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="buku_id" class="form-label">Judul Buku</label>
                                    <select name="buku_id" class="form-control">
                                        @foreach ($bukus as $b)
                                            <option value="{{ $b->id }}">{{ $b->judul }}</option>
                                        @endforeach
                                    </select>
                                    @error('buku_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_buku" class="form-label">Jumlah Buku</label>
                                    <input type="number" name="jumlah_buku" class="form-control" min="1">
                                    @error('jumlah_buku')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Full width bawah -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                                    <input type="date" name="tgl_pinjam" class="form-control">
                                    @error('tgl_pinjam')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
