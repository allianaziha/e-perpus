<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Alert;

use Illuminate\Http\Request;


class PinjamController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'jumlah_buku' => 'required|integer|min:1',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok < $request->jumlah_buku) {
            return back()->with('error', 'Stok buku tidak mencukupi.');
        }

        Peminjaman::create([
            'buku_id' => $buku->id,
            'user_id' => auth()->id(),
            'jumlah_buku' => $request->jumlah_buku,
            'tgl_pinjam' => now(),
            'tgl_jatuh_tempo' => now()->addDays(7),
            'status' => 'pending',
        ]);

        Alert::toast('Permintaan peminjaman dikirim! Menunggu persetujuan admin.', 'success')
        ->position('top-end'); 
    return back();
    }

    public function riwayat()
    {
        $riwayat = Peminjaman::with('buku')->where('user_id', auth()->id())->latest()->get();
        return view('riwayat', compact('riwayat'));
    }
    
}
