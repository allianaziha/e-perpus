@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4>Edit Banner</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="judul_utama" class="form-label">Judul Utama</label>
                                    <input type="text" name="judul_utama" 
                                           value="{{ old('judul_utama', $banner->judul_utama) }}" 
                                           class="form-control @error('judul_utama') is-invalid @enderror">
                                    @error('judul_utama') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul</label>
                                    <input type="text" name="judul" 
                                           value="{{ old('judul', $banner->judul) }}" 
                                           class="form-control @error('judul') is-invalid @enderror">
                                    @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="aktif" {{ $banner->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ $banner->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar Banner</label>
                                    @if ($banner->gambar)
                                        <div class="mb-2">
                                            <img src="{{ asset('images/banner/' . $banner->gambar) }}" 
                                                 alt="Gambar {{ $banner->judul_utama }}" 
                                                 width="150" class="img-thumbnail">
                                        </div>
                                    @endif
                                    <input type="file" name="gambar" 
                                           class="form-control @error('gambar') is-invalid @enderror">
                                    @error('gambar') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" 
                                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $banner->deskripsi) }}</textarea>
                            @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <a href="{{ route('admin.banner.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
