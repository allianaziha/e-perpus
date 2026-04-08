<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PerpanjanganRequest;
use Illuminate\Http\Request;
use Alert;

class PerpanjanganController extends Controller
{
    public function index()
    {
        // Ambil peminjaman yang sedang dipinjam dan belum dikembalikan
        $peminjamans = Peminjaman::with(['buku', 'perpanjanganRequests'])
            ->where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->whereDoesntHave('pengembalian')
            ->latest()
            ->get();

        // Ambil riwayat request perpanjangan user
        $perpanjanganRequests = PerpanjanganRequest::with(['peminjaman.buku', 'approvedBy'])
            ->whereHas('peminjaman', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('user.perpanjangan.index', compact('peminjamans', 'perpanjanganRequests'));
    }

    public function create(Peminjaman $peminjaman)
    {
        // Pastikan peminjaman milik user ini dan belum dikembalikan
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'dipinjam' || $peminjaman->pengembalian) {
            Alert::error('Gagal', 'Peminjaman ini tidak bisa diperpanjang');
            return redirect()->route('user.perpanjangan.index');
        }

        // Cek apakah sudah ada request perpanjangan yang pending
        $pendingRequest = $peminjaman->perpanjanganRequests()->where('status', 'pending')->first();
        if ($pendingRequest) {
            Alert::warning('Peringatan', 'Masih ada request perpanjangan yang belum diproses');
            return redirect()->route('user.perpanjangan.index');
        }

        $peminjaman->load('buku');

        return view('user.perpanjangan.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'lama_perpanjangan' => 'required|integer|min:1|max:30', // max 30 hari
            'alasan' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        // Pastikan peminjaman milik user ini
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        // Pastikan peminjaman masih aktif
        if ($peminjaman->status !== 'dipinjam' || $peminjaman->pengembalian) {
            Alert::error('Gagal', 'Peminjaman ini tidak bisa diperpanjang');
            return redirect()->route('user.perpanjangan.index');
        }

        // Cek apakah sudah ada request perpanjangan yang pending
        $pendingRequest = $peminjaman->perpanjanganRequests()->where('status', 'pending')->first();
        if ($pendingRequest) {
            Alert::warning('Peringatan', 'Masih ada request perpanjangan yang belum diproses');
            return redirect()->route('user.perpanjangan.index');
        }

        // Buat request perpanjangan
        PerpanjanganRequest::create([
            'peminjaman_id' => $peminjaman->id,
            'lama_perpanjangan' => $request->lama_perpanjangan,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        Alert::success('Berhasil', 'Request perpanjangan berhasil diajukan. Menunggu persetujuan admin/petugas.');
        return redirect()->route('user.perpanjangan.index');
    }

    public function show(PerpanjanganRequest $perpanjangan)
    {
        // Pastikan request perpanjangan milik user
        if ($perpanjangan->peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        $perpanjangan->load(['peminjaman.buku', 'approvedBy']);

        return view('user.perpanjangan.show', compact('perpanjangan'));
    }
}
