<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
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

        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $users = User::all();
        $bukus = Buku::all();
        return view('petugas.peminjaman.create', compact('users','bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'buku_id'     => 'required|exists:bukus,id',
            'jumlah_buku' => 'required|integer|min:1',
            'tgl_pinjam'  => 'required|date',
        ]);

        $tglPinjam     = $request->tgl_pinjam;
        // tgl_jatuh_tempo hanya dihitung jika status dipinjam
        $tglJatuhTempo = null;

        if (auth()->check() && in_array(auth()->user()->role, ['admin','petugas'])) {
            $status = 'dipinjam';
            // Jika langsung dipinjam, hitung tgl_jatuh_tempo 7 hari dari tgl_pinjam
            $tglJatuhTempo = Carbon::parse($tglPinjam)->addDays(7)->format('Y-m-d');
        } else {
            $status = 'pending';
        }

        $buku = Buku::findOrFail($request->buku_id);

        if ($status == 'dipinjam') {
            if ($request->jumlah_buku > $buku->stok) {
                return back()->with('error', 'Stok buku tidak cukup!');
            }
            $buku->stok -= $request->jumlah_buku;
            $buku->save();
        }

        Peminjaman::create([
            'kode_peminjaman' => 'PJ-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'user_id'         => $request->user_id,
            'buku_id'         => $request->buku_id,
            'jumlah_buku'     => $request->jumlah_buku,
            'tgl_pinjam'      => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'status'          => $status,
        ]);

        toast('Data berhasil disimpan', 'success');
        return redirect()->route('petugas.peminjaman.index');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user','buku']);
        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $users = User::all();
        $bukus = Buku::all();
        return view('petugas.peminjaman.edit', compact('peminjaman','users','bukus'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'buku_id'     => 'required|exists:bukus,id',
            'jumlah_buku' => 'required|integer|min:1',
            'tgl_pinjam'  => 'required|date',
            'status'      => 'in:pending,dipinjam,dikembalikan,ditolak',
        ]);

        $tglPinjam     = $request->tgl_pinjam;
        $tglJatuhTempo = Carbon::parse($tglPinjam)->addDays(7)->format('Y-m-d');

        $peminjaman->update([
            'user_id'         => $request->user_id,
            'buku_id'         => $request->buku_id,
            'jumlah_buku'     => $request->jumlah_buku,
            'tgl_pinjam'      => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'status'          => $request->status ?? $peminjaman->status,
        ]);

        Alert::info('Diupdate', 'Data peminjaman berhasil diupdate!');
        return redirect()->route('petugas.peminjaman.index');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // rollback stok jika status masih dipinjam
        if (in_array($peminjaman->status, ['dipinjam', 'dikembalikan'])) {
            $buku = $peminjaman->buku;
            if ($buku) {
                $buku->stok += $peminjaman->jumlah_buku;
                $buku->save();
            }
        }

        $peminjaman->delete();
        Alert::error('Dihapus', 'Data peminjaman berhasil dihapus!');
        return redirect()->route('petugas.peminjaman.index');
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            toast('Peminjaman sudah diproses', 'info');
            return redirect()->route('petugas.peminjaman.index');
        }

        $buku = $peminjaman->buku;

        if ($peminjaman->jumlah_buku > $buku->stok) {
            toast('Stok buku tidak cukup untuk peminjaman!', 'error');
            return redirect()->route('petugas.peminjaman.index');
        }

        $buku->stok -= $peminjaman->jumlah_buku;
        $buku->save();

        // Hitung tgl_pinjam dan tgl_jatuh_tempo saat approve (7 hari dari sekarang)
        $tglPinjam = now();
        $tglJatuhTempo = now()->addDays(7);
        
        $peminjaman->update([
            'status' => 'dipinjam',
            'tgl_pinjam' => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'is_read' => false
        ]);

        toast('Peminjaman disetujui', 'success');
        return redirect()->route('petugas.peminjaman.index');
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            toast('Peminjaman sudah diproses', 'info');
            return redirect()->route('petugas.peminjaman.index');
        }

        $peminjaman->update(['status' => 'ditolak']);
        Alert::warning('Ditolak', 'Peminjaman ditolak!');
        return redirect()->route('petugas.peminjaman.index');
    }

    public function notifikasi() 
    {
        $peminjaman = Peminjaman::with('user','buku','pengembalian')->latest()->get();
        return view('petugas.peminjaman.index', compact('peminjaman'));
    }
}
