@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Detail Buku
                </div>
                <div class="card-body">
                    
                    <p><strong>ID :</strong> {{ $buku->id }}</p>
                    <p><strong>Kode Buku :</strong> {{ $buku->kode_buku }}</p>
                    <p><strong>Judul :</strong> {{ $buku->judul }}</p>
                    <p><strong>Penulis :</strong> {{ $buku->penulis }}</p>
                    <p><strong>Penerbit :</strong> {{ $buku->penerbit }}</p>
                    <p><strong>Tahun Terbit :</strong> {{ $buku->tahun_terbit }}</p>
                    <p><strong>Stok :</strong> {{ $buku->stok }}</p>
                    <p><strong>Rak :</strong> {{ $buku->rak->nama ?? '-' }}</p>
                    <p><strong>Kategori :</strong> {{ $buku->kategori->nama ?? '-' }}</p>
                    
                    <p><strong>Gambar :</strong></p>
                    @if($buku->gambar)
                        <div class="mt-2">
                            <img src="{{ asset('images/buku/'.$buku->gambar) }}" 
                                 alt="{{ $buku->judul }}" 
                                 class="img-fluid rounded border"
                                 style="max-height: 250px;">
                        </div>
                    @else
                        <p><em>Tidak ada gambar</em></p>
                    @endif

                </div>
                <div class="card-footer">
                    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
