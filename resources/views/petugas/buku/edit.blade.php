@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4>Edit Buku</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kode_buku" class="form-label">Kode Buku</label>
                                    <input type="text" name="kode_buku" value="{{ $buku->kode_buku }}" 
                                        class="form-control @error('kode_buku') is-invalid @enderror">
                                    @error('kode_buku') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul</label>
                                    <input type="text" name="judul" value="{{ $buku->judul }}"
                                        class="form-control @error('judul') is-invalid @enderror">
                                    @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="penulis" class="form-label">Penulis</label>
                                    <input type="text" name="penulis" value="{{ $buku->penulis }}"
                                        class="form-control @error('penulis') is-invalid @enderror">
                                    @error('penulis') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="penerbit" class="form-label">Penerbit</label>
                                    <input type="text" name="penerbit" value="{{ $buku->penerbit }}"
                                        class="form-control @error('penerbit') is-invalid @enderror">
                                    @error('penerbit') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 3 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                    <input type="number" name="tahun_terbit" value="{{ $buku->tahun_terbit }}"
                                        class="form-control @error('tahun_terbit') is-invalid @enderror">
                                    @error('tahun_terbit') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" name="stok" value="{{ $buku->stok }}"
                                        class="form-control @error('stok') is-invalid @enderror">
                                    @error('stok') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rak_id" class="form-label">Rak</label>
                                    <select name="rak_id" class="form-control @error('rak_id') is-invalid @enderror">
                                        @foreach ($rak as $r)
                                            <option value="{{ $r->id }}" {{ $buku->rak_id == $r->id ? 'selected' : '' }}>
                                                {{ $r->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rak_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}" {{ $buku->kategori_id == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Field Gambar -->
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            @if ($buku->gambar)
                                <div class="mb-2">
                                    <img src="{{ asset('images/buku/' . $buku->gambar) }}"
                                        alt="Gambar {{ $buku->judul }}" width="120" class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror">
                            @error('gambar') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Field Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                            @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <a href="{{ route('petugas.buku.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
