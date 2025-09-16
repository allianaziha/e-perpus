@extends('layouts.backend')

@section ('title', 'Petugas perpus - Pengembalian')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataPengembalian th, #dataPengembalian td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pengembalian</h5>
                    <a href="{{ route('petugas.pengembalian.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle" id="dataPengembalian">
                            <thead class="table">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Nama User</th>
                                    <th class="text-center">Judul Buku</th>
                                    <th class="text-center">Tgl Jatuh Tempo</th>
                                    <th class="text-center">Tgl Kembali</th>
                                    <th class="text-center">Kondisi</th>
                                    <th class="text-center">Denda</th>
                                    <th class="text-center" style="width: 15%">Aksi</th>
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
                                        <td class="text-center">Rp {{ number_format($data->denda->sum('nominal'), 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('petugas.pengembalian.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>
                                            <a href="{{ route('petugas.pengembalian.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                            <form action="{{ route('petugas.pengembalian.destroy', $data->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus pengembalian ini?')"><i class="ti ti-trash"></i></button>
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
    new DataTable('#dataPengembalian');
</script>
@endpush
