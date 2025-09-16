@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between">
            <h5 class="mb-0">Daftar User</h5>
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus"></i> Tambah User
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table align-middle" id="dataUsers">
                <thead class="table">
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center" style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $index+1 }}</td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center">{{ $user->email }}</td>
                        <td class="text-center">
                            <span class="badge 
                                {{ $user->role == 'admin' ? 'bg-danger' : 
                                   ($user->role == 'petugas' ? 'bg-success' : 'bg-primary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-info btn-sm">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin hapus user ini?')" class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
