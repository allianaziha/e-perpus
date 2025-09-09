@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Data Denda
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table" id="dataDenda">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peminjaman</th>
                                    <th>Total Denda</th>
                                    <th>Sudah Dibayar</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dendas as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $data->pengembalian->peminjaman->buku->judul ?? '-' }} <br>
                                            <small>oleh {{ $data->pengembalian->peminjaman->user->name ?? '-' }}</small>
                                        </td>
                                        <td>Rp {{ number_format($data->nominal,0,',','.') }}</td>
                                        <td>Rp {{ number_format($data->dibayar,0,',','.') }}</td>
                                        <td>Rp {{ number_format($data->nominal - $data->dibayar,0,',','.') }}</td>
                                        <td>
                                            <span class="badge {{ $data->status == 'belum lunas' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucfirst($data->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('denda.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>
                                            <a href="{{ route('denda.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                            <form action="{{ route('denda.destroy', $data->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus denda ini?')"><i class="ti ti-trash"></i></button>
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
    new DataTable('#dataDenda');
</script>
@endpush
