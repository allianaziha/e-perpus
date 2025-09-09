@extends('layouts.backend')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Detail Rak
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $rak->id }}</p>
                    <p><strong>Nama Rak:</strong> {{ $rak->nama }}</p>
                    <p><strong>Kode Rak:</strong> {{ $rak->kode }}</p>
                    <p><strong>Lokasi Rak:</strong> {{ $rak->lokasi }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('rak.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    <a href="{{ route('rak.edit', $rak->id) }}" class="btn btn-sm btn-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
