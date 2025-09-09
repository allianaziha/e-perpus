<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use Illuminate\Http\Request;
use Alert;

class DendaController extends Controller
{
    public function index()
    {
        // Tampilkan semua denda dengan relasi lengkap
        $dendas = Denda::with('pengembalian.peminjaman.user', 'pengembalian.peminjaman.buku')->get();
        return view('denda.index', compact('dendas'));
    }

    public function show(Denda $denda)
    {
        // Detail denda tertentu
        $denda->load('pengembalian.peminjaman.user', 'pengembalian.peminjaman.buku');
        return view('denda.show', compact('denda'));
    }

    public function edit(Denda $denda)
    {
        // Halaman untuk membayar / mencicil denda
        return view('denda.edit', compact('denda'));
    }

    public function update(Request $request, Denda $denda)
    {
        $request->validate([
            'dibayar' => 'required|integer|min:1',
        ]);

        // Tambahkan pembayaran baru ke total
        $totalBayar = $denda->dibayar + $request->dibayar;

        // Tentukan status lunas atau belum
        $status = $totalBayar >= $denda->nominal ? 'lunas' : 'belum lunas';

        // Update data denda
        $denda->update([
            'dibayar' => $totalBayar,
            'status' => $status,
        ]);

        Alert::success('Berhasil', 'Pembayaran denda berhasil dicatat.');
        return redirect()->route('denda.index');
    }

    public function destroy(Denda $denda)
    {
        $denda->delete();

        Alert::success('Berhasil', 'Denda berhasil dihapus.');
        return redirect()->route('denda.index');
    }
}
