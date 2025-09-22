@extends('layouts.backend')

@section('title', 'Admin perpus - Kategori')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataKategori th, #dataKategori td {
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
    <h3 class="mb-3 fw-bold text-uppercase">Kategori</h3>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Kategori</h5>
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0" id="dataKategori">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Nama Kategori</th>
                                    <th class="text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $data)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $data->nama }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.kategori.show', $data->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                           <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.kategori.edit', $data->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                           <i class="ti ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.kategori.destroy', $data->id) }}" 
                                              method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<script>
    new DataTable('#dataKategori');
</script>
@endpush
