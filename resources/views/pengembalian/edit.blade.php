@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Edit Pengembalian
                </div>
                <div class="card-body">
                    <form action="{{ route('pengembalian.update', $pengembalian->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peminjaman_id" class="form-label">Peminjaman</label>
                                    <select name="peminjaman_id" class="form-control">
                                        @foreach($peminjamans as $p)
                                            <option value="{{ $p->id }}" {{ $pengembalian->peminjaman_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->user->name }} - {{ $p->buku->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('peminjaman_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kondisi" class="form-label">Kondisi Buku</label>
                                    <select name="kondisi" class="form-control">
                                        <option value="baik" {{ $pengembalian->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak" {{ $pengembalian->kondisi == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        <option value="hilang" {{ $pengembalian->kondisi == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                    </select>
                                    @error('kondisi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Full width -->
                        <div class="mb-3">
                            <label for="tgl_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" value="{{ $pengembalian->tgl_kembali }}">
                            @error('tgl_kembali')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <a href="{{ route('pengembalian.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
