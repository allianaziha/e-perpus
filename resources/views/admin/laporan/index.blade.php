@extends('layouts.backend')

@section ('title', 'Admin perpus - Laporan')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h5 class="mb-0">Laporan Perpustakaan</h5>
            <div>
                <form action="{{ route('admin.laporan.exportPDF') }}" method="GET" class="d-inline">
                    <input type="hidden" name="awal" value="{{ $awal }}">
                    <input type="hidden" name="akhir" value="{{ $akhir }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <button type="submit" class="btn btn-danger btn-sm">Export PDF</button>
                </form>
                <form action="{{ route('admin.laporan.exportExcel') }}" method="GET" class="d-inline">
                    <input type="hidden" name="awal" value="{{ $awal }}">
                    <input type="hidden" name="akhir" value="{{ $akhir }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <button type="submit" class="btn btn-success btn-sm">Export Excel</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            
            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row mb-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="col-md-3">
                    <label>Pilih Tabel</label>
                    <select name="tab" class="form-control" onchange="this.form.submit()">
                        <option value="buku" {{ $tab=='buku'?'selected':'' }}>Buku</option>
                        <option value="peminjaman" {{ $tab=='peminjaman'?'selected':'' }}>Peminjaman</option>
                        <option value="pengembalian" {{ $tab=='pengembalian'?'selected':'' }}>Pengembalian</option>
                        <option value="denda" {{ $tab=='denda'?'selected':'' }}>Denda</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" name="awal" value="{{ $awal }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="akhir" value="{{ $akhir }}" class="form-control">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            {{-- Tabs Status --}}
            <ul class="nav nav-tabs" id="statusTab" role="tablist">
                @if($tab=='peminjaman')
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'peminjaman','status'=>'']) }}">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='dipinjam' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'peminjaman','status'=>'dipinjam']) }}">Dipinjam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='dikembalikan' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'peminjaman','status'=>'dikembalikan']) }}">Dikembalikan</a>
                    </li>
                @elseif($tab=='pengembalian')
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'pengembalian','status'=>'']) }}">Semua</a>
                    </li>
                @elseif($tab=='denda')
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'denda','status'=>'']) }}">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='lunas' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'denda','status'=>'lunas']) }}">Lunas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status=='belum_lunas' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['tab'=>'denda','status'=>'belum_lunas']) }}">Belum Lunas</a>
                    </li>
                @elseif($tab=='buku')
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Semua Buku</a>
                    </li>
                @endif
            </ul>

            <div class="tab-content mt-3">
                {{-- Buku --}}
                <div class="tab-pane fade {{ $tab=='buku'?'show active':'' }}" id="buku">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buku as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->penerbit }}</td>
                                <td>{{ $item->tahun_terbit }}</td>
                                <td>{{ $item->stok }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Peminjaman --}}
                <div class="tab-pane fade {{ $tab=='peminjaman'?'show active':'' }}" id="peminjaman">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Buku</th>
                                <th>Jumlah</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->buku->judul }}</td>
                                <td>{{ $item->jumlah_buku }}</td>
                                <td>{{ $item->tgl_pinjam }}</td>
                                <td>{{ $item->tgl_jatuh_tempo }}</td>
                                <td>
                                    @if($item->status == 'dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pengembalian --}}
                <div class="tab-pane fade {{ $tab=='pengembalian'?'show active':'' }}" id="pengembalian">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Buku</th>
                                <th>Tgl Kembali</th>
                                <th>Kondisi</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengembalian as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $item->peminjaman->user->name }}</td>
                                <td>{{ $item->peminjaman->buku->judul }}</td>
                                <td>{{ $item->tgl_kembali }}</td>
                                <td>
                                    @if ($item->kondisi == 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif ($item->kondisi == 'rusak')
                                        <span class="badge bg-warning text-dark">Rusak</span>
                                    @else
                                        <span class="badge bg-danger">Hilang</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->denda->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Denda --}}
                <div class="tab-pane fade {{ $tab=='denda'?'show active':'' }}" id="denda">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Buku</th>
                                <th>Total Denda</th>
                                <th>Sudah Dibayar</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($denda as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $item->pengembalian->peminjaman->user->name ?? '-' }}</td>
                                <td>{{ $item->pengembalian->peminjaman->buku->judul ?? '-' }}</td>
                                <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
                                <td>Rp {{ number_format($item->dibayar,0,',','.') }}</td>
                                <td>Rp {{ number_format($item->nominal - $item->dibayar,0,',','.') }}</td>
                                <td>
                                    @if($item->status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
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
@endsection
