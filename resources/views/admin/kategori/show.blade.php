@extends('layouts.backend')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Detail Kategori
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
