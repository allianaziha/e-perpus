@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Tambah Buku</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kode_buku" class="form-label">Kode Buku</label>
                                    <input type="text" name="kode_buku" class="form-control" placeholder="Masukkan kode buku">
                                    @error('kode_buku') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku">
                                    @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="penulis" class="form-label">Penulis</label>
                                    <input type="text" name="penulis" class="form-control" placeholder="Masukkan nama penulis">
                                    @error('penulis') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="penerbit" class="form-label">Penerbit</label>
                                    <input type="text" name="penerbit" class="form-control" placeholder="Masukkan nama penerbit">
                                    @error('penerbit') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 3 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                    <input type="number" name="tahun_terbit" class="form-control" placeholder="Contoh: 2024">
                                    @error('tahun_terbit') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" name="stok" class="form-control" placeholder="Jumlah stok">
                                    @error('stok') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rak_id" class="form-label">Rak</label>
                                    <select name="rak_id" class="form-control">
                                        <option value="" disabled selected>Pilih rak</option>
                                        @foreach ($rak as $r)
                                            <option value="{{ $r->id }}">{{ $r->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('rak_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-control">
                                        <option value="" disabled selected>Pilih kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Field Gambar -->
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" name="gambar" class="form-control">
                            @error('gambar') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan deskripsi singkat buku"></textarea>
                            @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <a href="{{ route('admin.buku.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
