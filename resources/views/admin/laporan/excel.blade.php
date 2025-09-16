<table>
    <tr><th colspan="6">Data Buku</th></tr>
    <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Penerbit</th>
        <th>Tahun</th>
        <th>Stok</th>
    </tr>
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
</table>

<br>

<table>
    <tr><th colspan="7">Data Peminjaman</th></tr>
    <tr>
        <th>No</th>
        <th>Nama Peminjam</th>
        <th>Buku</th>
        <th>Jumlah</th>
        <th>Tgl Pinjam</th>
        <th>Tgl Jatuh Tempo</th>
        <th>Status</th>
    </tr>
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
</table>

<br>

<table>
    <tr><th colspan="6">Data Pengembalian</th></tr>
    <tr>
        <th>No</th>
        <th>Nama Peminjam</th>
        <th>Buku</th>
        <th>Tgl Kembali</th>
        <th>Kondisi</th>
        <th>Denda</th>
    </tr>
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
</table>

<br>

<table>
    <tr><th colspan="7">Data Denda</th></tr>
    <tr>
        <th>No</th>
        <th>Nama Peminjam</th>
        <th>Buku</th>
        <th>Total Denda</th>
        <th>Sudah Dibayar</th>
        <th>Sisa</th>
        <th>Status</th>
    </tr>
    @foreach($denda as $i => $d)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $d->pengembalian->peminjaman->user->name ?? '-' }}</td>
        <td>{{ $d->pengembalian->peminjaman->buku->judul ?? '-' }}</td>
        <td>Rp {{ number_format($d->nominal,0,',','.') }}</td>
        <td>Rp {{ number_format($d->dibayar,0,',','.') }}</td>
        <td>Rp {{ number_format($d->nominal - $d->dibayar,0,',','.') }}</td>
        <td>{{ ucfirst($d->status) }}</td>
    </tr>
    @endforeach
</table>
