<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Denda;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Alert;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the user's loans to return.
     */
    public function index()
    {
        // Ambil semua peminjaman yang status "dipinjam" dan belum dikembalikan
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->doesntHave('pengembalian')
            ->latest()
            ->get();

        // Ambil riwayat pengembalian user
        $pengembalians = Pengembalian::with(['peminjaman.buku', 'denda'])
            ->whereHas('peminjaman', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('user.pengembalian.index', compact('peminjamans', 'pengembalians'));
    }

    /**
     * Show the form for creating a new return.
     */
    public function create(Peminjaman $peminjaman)
    {
        // Pastikan peminjaman milik user ini dan belum dikembalikan
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'dipinjam' || $peminjaman->pengembalian) {
            Alert::error('Gagal', 'Peminjaman ini tidak bisa dikembalikan');
            return redirect()->route('user.pengembalian.index');
        }

        $peminjaman->load('buku');

        return view('user.pengembalian.create', compact('peminjaman'));
    }

    /**
     * Store a newly created return in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'kondisi'       => 'required|in:baik,rusak,hilang',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        // Pastikan peminjaman milik user ini dan belum dikembalikan
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'dipinjam' || $peminjaman->pengembalian) {
            Alert::error('Gagal', 'Peminjaman ini tidak bisa dikembalikan');
            return redirect()->route('user.pengembalian.index');
        }

        // Buat pengembalian record dengan tanggal hari ini
        $pengembalian = Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tgl_kembali'   => now()->toDateString(),
            'kondisi'       => $request->kondisi,
        ]);

        // Update status peminjaman menjadi dikembalikan
        $peminjaman->update(['status' => 'dikembalikan']);

        // Tambahkan stok buku kembali
        $buku = $peminjaman->buku;
        $buku->stok += $peminjaman->jumlah_buku;
        $buku->save();

        // ===== Hitung Denda =====
        $tgl_jatuh_tempo = Carbon::parse($peminjaman->tgl_jatuh_tempo);
        $tgl_kembali     = Carbon::parse($pengembalian->tgl_kembali);

        $jumlah_hari = 0;
        $total_denda = 0;
        $jenis = [];

        // Telat
        if ($tgl_kembali->gt($tgl_jatuh_tempo)) {
            $jumlah_hari = $tgl_jatuh_tempo->diffInDays($tgl_kembali);
            $total_denda += $jumlah_hari * 5000;
            $jenis[] = 'telat';
        }

        // Kondisi Buku
        if ($request->kondisi === 'rusak') {
            $total_denda += 20000;
            $jenis[] = 'rusak';
        } elseif ($request->kondisi === 'hilang') {
            $total_denda += 20000;
            $jenis[] = 'hilang';
        }

        // Simpan denda kalau ada
        if ($total_denda > 0) {
            Denda::create([
                'pengembalian_id' => $pengembalian->id,
                'jenis'           => implode(',', $jenis),
                'jumlah_hari'     => $jumlah_hari > 0 ? $jumlah_hari : null,
                'nominal'         => $total_denda,
            ]);
        }

        Alert::success('Berhasil', 'Pengembalian buku berhasil dicatat!');
        return redirect()->route('user.pengembalian.index');
    }

    /**
     * Display the specified return.
     */
    public function show(Pengembalian $pengembalian)
    {
        // Pastikan pengembalian ini milik user
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        $pengembalian->load(['peminjaman.buku', 'denda']);

        return view('user.pengembalian.show', compact('pengembalian'));
    }
}

