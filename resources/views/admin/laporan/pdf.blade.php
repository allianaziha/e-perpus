<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpustakaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2, h4 { margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2 align="center">Laporan Perpustakaan</h2>

    <h4>Data Buku</h4>
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
            @foreach($buku as $i => $b)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $b->judul }}</td>
                <td>{{ $b->penulis }}</td>
                <td>{{ $b->penerbit }}</td>
                <td>{{ $b->tahun_terbit }}</td>
                <td>{{ $b->stok }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Data Peminjaman</h4>
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
            @foreach($peminjaman as $i => $p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->user->name }}</td>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->jumlah_buku }}</td>
                <td>{{ $p->tgl_pinjam }}</td>
                <td>{{ $p->tgl_jatuh_tempo }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Data Pengembalian</h4>
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
            @foreach($pengembalian as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->peminjaman->user->name }}</td>
                <td>{{ $k->peminjaman->buku->judul }}</td>
                <td>{{ $k->tgl_kembali }}</td>
                <td>{{ ucfirst($k->kondisi) }}</td>
                <td>Rp {{ number_format($k->denda->sum('nominal'), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Data Denda</h4>
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
            @foreach($denda as $i => $d)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $d->pengembalian->peminjaman->user->name ?? '-' }}</td>
                <td>{{ $d->pengembalian->peminjaman->buku->judul ?? '-' }}</td>
                <td>Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($d->dibayar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($d->nominal - $d->dibayar, 0, ',', '.') }}</td>
                <td>{{ ucfirst($d->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
