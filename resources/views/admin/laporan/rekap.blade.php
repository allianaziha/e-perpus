<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        .badge { padding: 3px 6px; color: white; border-radius: 4px; font-size: 10px; }
        .bg-success { background-color: green; }
        .bg-warning { background-color: orange; }
        .bg-danger { background-color: red; }
    </style>
</head>
<body>

<h3>Laporan Perpustakaan</h3>
<p>Tanggal: {{ $awal ?? '-' }} s/d {{ $akhir ?? '-' }}</p>

{{-- Buku --}}
@if($tab == 'buku')
<table>
    <thead>
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
@endif

{{-- Peminjaman --}}
@if($tab == 'peminjaman')
<table>
    <thead>
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
                    <span class="badge bg-warning">Dipinjam</span>
                @else
                    <span class="badge bg-success">Dikembalikan</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Pengembalian --}}
@if($tab == 'pengembalian')
<table>
    <thead>
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
                @if($item->kondisi == 'baik')
                    <span class="badge bg-success">Baik</span>
                @elseif($item->kondisi == 'rusak')
                    <span class="badge bg-warning">Rusak</span>
                @else
                    <span class="badge bg-danger">Hilang</span>
                @endif
            </td>
            <td>Rp {{ number_format(optional($item->denda)->nominal ?? 0,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Denda --}}
@if($tab == 'denda')
<table>
    <thead>
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
@endif

</body>
</html>
