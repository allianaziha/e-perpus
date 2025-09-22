@extends('layouts.backend')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white">
                   <h5> Detail User</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Nama Lengkap:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Password:</strong> {{ $user->password }}</p> 
                    {{-- ini hash password --}}
                    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
