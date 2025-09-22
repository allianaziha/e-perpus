@extends('layouts.backend')

@section ('title', 'Admin perpus - Pengembalian')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataPengembalian th, #dataPengembalian td {
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
    <h3 class="mb-3 fw-bold text-uppercase">PENGEMBALIAN</h3>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Pengembalian</h5>
                    <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0" id="dataPengembalian">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Nama User</th>
                                    <th class="text-center">Judul Buku</th>
                                    <th class="text-center">Tgl Jatuh Tempo</th>
                                    <th class="text-center">Tgl Kembali</th>
                                    <th class="text-center">Kondisi</th>
                                    <th class="text-center">Denda</th>
                                    <th class="text-center" style="width: 18%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengembalians as $index => $data)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $data->peminjaman->user->name }}</td>
                                        <td class="text-center">{{ $data->peminjaman->buku->judul }}</td>
                                        <td class="text-center">{{ $data->peminjaman->tgl_jatuh_tempo }}</td>
                                        <td class="text-center">{{ $data->tgl_kembali }}</td>
                                        <td class="text-center">
                                            @if ($data->kondisi == 'baik')
                                                <span class="badge bg-success">Baik</span>
                                            @elseif ($data->kondisi == 'rusak')
                                                <span class="badge bg-warning text-dark">Rusak</span>
                                            @else
                                                <span class="badge bg-danger">Hilang</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            Rp {{ number_format(optional($data->denda)->nominal ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.pengembalian.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.pengembalian.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.pengembalian.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengembalian ini?')">
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
    new DataTable('#dataPengembalian');
</script>
@endpush
