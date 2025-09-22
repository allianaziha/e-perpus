@extends('layouts.backend')

@section ('title', 'Admin perpus - User')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataUsers th, #dataUsers td {
        vertical-align: middle;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Judul Besar --}}
    <h3 class="mb-3 fw-bold text-uppercase">USER</h3>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data User</h5>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah User
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0" id="dataUsers">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center" style="width: 18%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">
                                        <span class="badge
                                            {{ $user->role == 'admin' ? 'bg-danger' :
                                               ($user->role == 'petugas' ? 'bg-success' : 'bg-primary') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<script>
    new DataTable('#dataUsers');
</script>
@endpush
