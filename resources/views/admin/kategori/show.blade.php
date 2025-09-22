@extends('layouts.backend')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5>Detail Kategori</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $kategori->id }}</p>
                    <p><strong>Nama:</strong> {{ $kategori->nama }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
