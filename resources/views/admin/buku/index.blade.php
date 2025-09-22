@extends('layouts.backend')

@section ('title', 'Admin perpus - Buku')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataBuku th, #dataBuku td {
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
    <h3 class="mb-3 fw-bold text-uppercase">BUKU</h3>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Buku</h5>
                    <a href="{{ route('admin.buku.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0" id="dataBuku">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Kode Buku</th>
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">Penulis</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Gambar</th>
                                    <th class="">deskripsi</th>
                                    <th class="text-center" style="width: 18%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buku as $index => $data)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $data->kode_buku }}</td>
                                    <td class="text-center">{{ $data->judul }}</td>
                                    <td class="text-center">{{ $data->penulis }}</td>
                                    <td class="text-center">{{ $data->stok }}</td>
                                    <td class="text-center">
                                        @if($data->gambar)
                                            <img src="{{ asset('images/buku/'.$data->gambar) }}" 
                                                 alt="{{ $data->judul }}" 
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 class="rounded shadow-sm">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::of($data->deskripsi)->explode(' ')->first() }}...</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.buku.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>
                                            <a href="{{ route('admin.buku.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                            <form action="{{ route('admin.buku.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
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
    new DataTable('#dataBuku');
</script>
@endpush
