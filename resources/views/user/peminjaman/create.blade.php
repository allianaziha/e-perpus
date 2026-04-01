@extends('layouts.backend')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

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

                        {{-- USER OTOMATIS --}}
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama User</label>
                                    <input type="text" class="form-control" 
                                           value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>

                            {{-- PILIH BUKU (SEARCH) --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="buku_id" class="form-label">Judul Buku</label>
                                    <select name="buku_id" class="form-control select2">
                                        <option value="">-- Cari Buku --</option>
                                        @foreach ($bukus as $b)
                                            <option value="{{ $b->id }}">
                                                {{ $b->judul }} (stok: {{ $b->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('buku_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- JUMLAH BUKU --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_buku" class="form-label">Jumlah Buku</label>
                                    <input type="number" name="jumlah_buku" class="form-control" min="1">
                                    @error('jumlah_buku')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- TANGGAL PINJAM --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                                    <input type="date" name="tgl_pinjam" class="form-control"
                                           value="{{ date('Y-m-d') }}">
                                    @error('tgl_pinjam')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

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


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Cari buku...",
        allowClear: true
    });
});
</script>
@endsection