@extends('layouts.backend')

@section ('title', 'Admin perpus - Denda')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataDenda th, #dataDenda td {
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
    <h3 class="mb-3 fw-bold text-uppercase">Denda</h3>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Denda</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle" id="dataDenda">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Peminjaman</th>
                                    <th class="text-center">Total Denda</th>
                                    <th class="text-center">Sudah Dibayar</th>
                                    <th class="text-center">Sisa</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 18%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dendas as $index => $data)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $data->pengembalian->peminjaman->buku->judul ?? '-' }}</strong><br>
                                            <small>oleh {{ $data->pengembalian->peminjaman->user->name ?? '-' }}</small>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($data->nominal,0,',','.') }}</td>
                                        <td class="text-center">Rp {{ number_format($data->dibayar,0,',','.') }}</td>
                                        <td class="text-center">Rp {{ number_format($data->nominal - $data->dibayar,0,',','.') }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $data->status == 'belum lunas' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucfirst($data->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.denda.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>
                                                <a href="{{ route('admin.denda.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                                <form action="{{ route('admin.denda.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus denda ini?')">
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
    new DataTable('#dataDenda');
</script>
@endpush

