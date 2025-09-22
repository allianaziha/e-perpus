@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Detail Buku</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <p><strong>ID :</strong> {{ $buku->id }}</p>
                            <p><strong>Kode Buku :</strong> {{ $buku->kode_buku }}</p>
                            <p><strong>Judul :</strong> {{ $buku->judul }}</p>
                            <p><strong>Penulis :</strong> {{ $buku->penulis }}</p>
                            <p><strong>Penerbit :</strong> {{ $buku->penerbit }}</p>
                            <p><strong>Tahun Terbit :</strong> {{ $buku->tahun_terbit }}</p>
                            <p><strong>Stok :</strong> {{ $buku->stok }}</p>
                            <p><strong>Rak :</strong> {{ $buku->rak->nama ?? '-' }}</p>
                            <p><strong>Kategori :</strong> {{ $buku->kategori->nama ?? '-' }}</p>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <p><strong>Gambar :</strong></p>
                            @if($buku->gambar)
                                <div class="mb-3">
                                    <img src="{{ asset('images/buku/'.$buku->gambar) }}" 
                                        alt="{{ $buku->judul }}" 
                                        class="img-fluid rounded border"
                                        style="max-height: 250px;">
                                </div>
                            @else
                                <p><em>Tidak ada gambar</em></p>
                            @endif
                        </div>
                    </div>

                    <!-- Deskripsi Full Width -->
                    <div class="mt-3">
                        <p><strong>Deskripsi :</strong></p>
                        <div class="border rounded p-3 bg-light">
                            {{ $buku->deskripsi ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                    <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
