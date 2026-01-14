@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white ">
            <h5>Tambah User Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('petugas.user.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="ti ti-check"></i> Simpan
                </button>
                <a href="{{ route('petugas.user.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
