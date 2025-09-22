@extends('layouts.backend')

@section ('title', 'Petugas perpus - Peminjaman')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<style>
    #dataPeminjaman th, #dataPeminjaman td {
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
    <h3 class="mb-3 fw-bold text-uppercase">PEMINJAMAN</h3>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Peminjaman</h5>
                    <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0" id="dataPeminjaman">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center">Nama User</th>
                                    <th class="text-center">Judul Buku</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Tgl Pinjam</th>
                                    <th class="text-center">Tgl Jatuh Tempo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($peminjaman as $index => $data)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $data->user->name }}</td>
                                        <td class="text-center">{{ $data->buku->judul }}</td>
                                        <td class="text-center">{{ $data->jumlah_buku }}</td>
                                        <td class="text-center">{{ $data->tgl_pinjam }}</td>
                                        <td class="text-center">{{ $data->tgl_jatuh_tempo }}</td>
                                        <td class="text-center">
                                            @if ($data->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($data->status == 'dipinjam')
                                                <span class="badge bg-secondary">Dipinjam</span>
                                            @elseif ($data->status == 'dikembalikan')
                                                <span class="badge bg-success">kembali</span>
                                            @elseif ($data->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                {{-- Tombol detail --}}
                                                <a href="{{ route('petugas.peminjaman.show', $data->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>

                                                @if ($data->status == 'pending')
                                                    {{-- ACC & Tolak --}}
                                                    <form action="{{ route('petugas.peminjaman.approve', $data->id) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui"><i class="ti ti-check"></i></button>
                                                    </form>
                                                    <form action="{{ route('petugas.peminjaman.reject', $data->id) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak"><i class="ti ti-x"></i></button>
                                                    </form>
                                                @elseif ($data->status == 'dipinjam')
                                                    {{-- Edit & Hapus --}}
                                                    <a href="{{ route('petugas.peminjaman.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                                    <form action="{{ route('petugas.peminjaman.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                @elseif ($data->status == 'ditolak')
                                                    {{-- Hapus --}}
                                                    <form action="{{ route('petugas.peminjaman.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                @elseif ($data->status == 'dikembalikan')
                                                    {{-- Detail aja --}}
                                                @endif
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
    new DataTable('#dataPeminjaman');
</script>
@endpush
