@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white text-dark">
            <h5>Edit User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password (kosongkan jika tidak diganti)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-refresh"></i> Update
                </button>
                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
