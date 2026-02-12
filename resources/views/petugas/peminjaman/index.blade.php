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
     <hr style="border: 0; border-top: 1px solid #e0e0e0; margin: 10px 0;">
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
                                @php
                                    // Kelompokkan peminjaman berdasarkan user_id dan tgl_pinjam (termasuk jam)
                                    $grouped = $peminjaman->groupBy(function($item) {
                                        return $item->user_id . '|' . $item->tgl_pinjam;
                                    });
                                    $counter = 0;
                                @endphp
                                @foreach ($grouped as $group)
                                    @php
                                        $counter++;
                                        $firstItem = $group->first();
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $counter }}</td>
                                        <td class="text-center">{{ $firstItem->user->name }}</td>
                                        <td class="text-center">
                                            <ul class="mb-0 ps-3" style="text-align: left;">
                                                @foreach ($group as $item)
                                                    <li>{{ $item->buku->judul }} ({{ $item->jumlah_buku }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $totalJumlah = $group->sum('jumlah_buku');
                                            @endphp
                                            {{ $totalJumlah }}
                                        </td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($firstItem->tgl_pinjam)->format('d-m-Y H:i') }}</td>
                                        <td class="text-center">
                                            @if ($firstItem->status == 'pending')
                                                <span class="text-muted">-</span>
                                            @else
                                                {{ \Carbon\Carbon::parse($firstItem->tgl_jatuh_tempo)->format('d-m-Y H:i') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($firstItem->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($firstItem->status == 'dipinjam')
                                                <span class="badge bg-secondary">Dipinjam</span>
                                            @elseif ($firstItem->status == 'dikembalikan')
                                                <span class="badge bg-success">kembali</span>
                                            @elseif ($firstItem->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                {{-- Tombol detail (hanya untuk item pertama) --}}
                                                <a href="{{ route('petugas.peminjaman.show', $firstItem->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>

                                                @if ($firstItem->status == 'pending')
                                                    {{-- ACC & Tolak --}}
                                                    @php
                                                        // Ambil ID pertama untuk approve/reject seluruh group
                                                        $actionId = $firstItem->id;
                                                    @endphp
                                                    <form action="{{ route('petugas.peminjaman.approve', $actionId) }}" method="POST" onsubmit="return confirm('Setujui semua peminjaman ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui"><i class="ti ti-check"></i></button>
                                                    </form>
                                                    <form action="{{ route('petugas.peminjaman.reject', $actionId) }}" method="POST" onsubmit="return confirm('Tolak semua peminjaman ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak"><i class="ti ti-x"></i></button>
                                                    </form>
                                                @elseif ($firstItem->status == 'dipinjam')
                                                    {{-- Edit & Hapus --}}
                                                    <a href="{{ route('petugas.peminjaman.edit', $firstItem->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                                    <form action="{{ route('petugas.peminjaman.destroy', $firstItem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua peminjaman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                @elseif ($firstItem->status == 'ditolak')
                                                    {{-- Hapus --}}
                                                    <form action="{{ route('petugas.peminjaman.destroy', $firstItem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                @elseif ($firstItem->status == 'dikembalikan')
                                                    {{-- Kalau sudah dikembalikan → bisa edit & hapus juga --}}
                                                    <a href="{{ route('petugas.peminjaman.edit', $firstItem->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('petugas.peminjaman.destroy', $firstItem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua peminjaman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                                    </form>
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
