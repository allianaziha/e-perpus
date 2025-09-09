@extends('layouts.backend')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Daftar Buku
                    <a href="{{ route('buku.create') }}" class="btn btn-info btn-sm" style="color:white; float:right;">
                        Tambah
                    </a>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table" id="dataBuku">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Buku</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun Terbit</th>
                                    <th>Stok</th>
                                    <th>Gambar</th>
                                    <th>Rak</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($buku as $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data->kode_buku }}</td>
                                        <td>{{ $data->judul }}</td>
                                        <td>{{ $data->penulis }}</td>
                                        <td>{{ $data->penerbit }}</td>
                                        <td>{{ $data->tahun_terbit }}</td>
                                        <td>{{ $data->stok }}</td>
                                        <td>
                                            @if($data->gambar)
                                                <img src="{{ asset('images/buku/'.$data->gambar) }}" alt="{{ $data->judul }}" style="width: 80px; height: 80px; object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>{{ $data->rak->nama }}</td>
                                        <td>{{ $data->kategori->nama }}</td>
                                        <td>
                                            <a href="{{ route('buku.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>
                                            <a href="{{ route('buku.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                                            <form action="{{ route('buku.destroy', $data->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus buku ini?')"><i class="ti ti-trash"></i></button>
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
    new DataTable('#dataBuku');
</script>
@endpush
