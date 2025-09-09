<?php

namespace App\Http\Controllers;

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

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $users = User::all();
        $bukus = Buku::all();
        return view('peminjaman.create', compact('users','bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tgl_pinjam' => 'required|date',
        ]);

        // Carbon otomatis tambah 7 hari
        $tglPinjam = $request->tgl_pinjam;
        $tglJatuhTempo = Carbon::parse($tglPinjam)->addDays(7)->format('Y-m-d');

        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tgl_pinjam' => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'status' => 'dipinjam',
        ]);

        toast('Data berhasil disimpan', 'success');
        return redirect()->route('peminjaman.index');
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $users = User::all();
        $bukus = Buku::all();
        return view('peminjaman.edit', compact('peminjaman','users','bukus'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'buku_id'   => 'required|exists:bukus,id',
            'tgl_pinjam'=> 'required|date',
        ]);

        $tglPinjam = $request->tgl_pinjam;
        $tglJatuhTempo = Carbon::parse($tglPinjam)->addDays(7)->format('Y-m-d');

        $peminjaman->update([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tgl_pinjam' => $tglPinjam,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            // status jangan diupdate manual
        ]);

        Alert::info('Diupdate', 'Data peminjaman berhasil diupdate!');
        return redirect()->route('peminjaman.index');
    }


    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        Alert::error('Dihapus', 'Data peminjaman berhasil dihapus!');
        return redirect()->route('peminjaman.index');
    }
}
