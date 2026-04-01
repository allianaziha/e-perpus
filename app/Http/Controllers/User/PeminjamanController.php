<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Alert;

class PeminjamanController extends Controller
{

    public function index()
    {
        $peminjaman = Peminjaman::with(['user','buku'])->latest()->get();

        $title = 'Hapus Peminjaman!';
        $text  = 'Apakah anda yakin ingin menghapus data ini?';
        confirmDelete($title, $text);

        return view('user.peminjaman.index', compact('peminjaman'));
    }


    public function create()
    {
        $bukus = Buku::all();

        // generate kode otomatis
        $last = Peminjaman::latest()->first();

        if ($last) {
            $number = (int) substr($last->kode_peminjaman, -3) + 1;
        } else {
            $number = 1;
        }

        $kode = 'PJM-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        return view('user.peminjaman.create', compact('bukus','kode'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'buku_id'     => 'required|exists:bukus,id',
            'jumlah_buku' => 'required|integer|min:1',
            'tgl_pinjam'  => 'required|date',
        ]);

        $tglPinjam     = $request->tgl_pinjam;
        $tglJatuhTempo = Carbon::parse($tglPinjam)->addDays(7)->format('Y-m-d');

        // generate kode
        $last = Peminjaman::latest()->first();

        if ($last) {
            $number = (int) substr($last->kode_peminjaman, -3) + 1;
        } else {
            $number = 1;
        }

        $kode = 'PJM-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        $buku = Buku::findOrFail($request->buku_id);

        if ($request->jumlah_buku > $buku->stok) {
            return back()->with('error', 'Stok buku tidak cukup!');
        }

        Peminjaman::create([
            'kode_peminjaman' => $kode,
            'user_id'         => auth()->id(),
            'buku_id'         => $request->buku_id,
            'jumlah_buku'     => $request->jumlah_buku,
            'tgl_pinjam'      => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'status'          => 'pending',
        ]);

        toast('Data berhasil disimpan', 'success');
        return redirect()->route('user.peminjaman.index');
    }


    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user','buku']);
        return view('user.peminjaman.show', compact('peminjaman'));
    }


    public function edit(Peminjaman $peminjaman)
    {
        $bukus = Buku::all();
        return view('user.peminjaman.edit', compact('peminjaman','bukus'));
    }


    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'buku_id'     => 'required|exists:bukus,id',
            'jumlah_buku' => 'required|integer|min:1',
            'tgl_pinjam'  => 'required|date',
            'status'      => 'in:pending,dipinjam,dikembalikan,ditolak',
        ]);

        $tglPinjam     = $request->tgl_pinjam;
        $tglJatuhTempo = Carbon::parse($tglPinjam)->addDays(7)->format('Y-m-d');

        $peminjaman->update([
            'buku_id'         => $request->buku_id,
            'jumlah_buku'     => $request->jumlah_buku,
            'tgl_pinjam'      => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'status'          => $request->status ?? $peminjaman->status,
        ]);

        Alert::info('Diupdate', 'Data peminjaman berhasil diupdate!');
        return redirect()->route('user.peminjaman.index');
    }


    public function destroy(Peminjaman $peminjaman)
    {
        // rollback stok
        if (in_array($peminjaman->status, ['dipinjam', 'dikembalikan'])) {

            $buku = $peminjaman->buku;

            if ($buku) {
                $buku->stok += $peminjaman->jumlah_buku;
                $buku->save();
            }
        }

        $peminjaman->delete();

        Alert::error('Dihapus', 'Data peminjaman berhasil dihapus!');
        return redirect()->route('user.peminjaman.index');
    }


    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            toast('Peminjaman sudah diproses', 'info');
            return redirect()->route('user.peminjaman.index');
        }

        $buku = $peminjaman->buku;

        if ($peminjaman->jumlah_buku > $buku->stok) {
            toast('Stok buku tidak cukup!', 'error');
            return redirect()->route('user.peminjaman.index');
        }

        $buku->stok -= $peminjaman->jumlah_buku;
        $buku->save();

        $peminjaman->update([
            'status' => 'dipinjam'
        ]);

        toast('Peminjaman disetujui', 'success');
        return redirect()->route('user.peminjaman.index');
    }

    public function batal(Peminjaman $peminjaman)
    {
        // hanya bisa dibatalkan jika masih pending
        if ($peminjaman->status !== 'pending') {
            toast('Peminjaman tidak bisa dibatalkan!', 'error');
            return redirect()->route('user.peminjaman.index');
        }

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        toast('Peminjaman berhasil dibatalkan', 'success');
        return redirect()->route('user.peminjaman.index');
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            toast('Peminjaman sudah diproses', 'info');
            return redirect()->route('user.peminjaman.index');
        }

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        Alert::warning('Ditolak', 'Peminjaman ditolak!');
        return redirect()->route('user.peminjaman.index');
    }


    public function notifikasi()
    {
        $peminjaman = Peminjaman::with('user','buku')->latest()->get();

        return view('user.peminjaman.index', compact('peminjaman'));
    }

}