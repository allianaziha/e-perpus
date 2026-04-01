<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\ChartPinjam;
use Illuminate\Http\Request;
use Alert;

class PinjamController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array'
        ]);

        $items = ChartPinjam::with('buku')
            ->whereIn('id', $request->selected_items)
            ->where('user_id', auth()->id())
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada buku dipilih.');
        }

        foreach ($items as $item) {

            $buku = Buku::find($item->buku_id);

            if ($buku->stok < $item->qty) {
                return back()->with('error', 'Stok buku "' . $buku->judul . '" tidak mencukupi.');
            }

            // Kurangi stok
            $buku->stok -= $item->qty;
            $buku->save();

            // Simpan peminjaman
            Peminjaman::create([
                'buku_id' => $buku->id,
                'user_id' => auth()->id(),
                'jumlah_buku' => $item->qty,
                'tgl_pinjam' => now(),
                'tgl_jatuh_tempo' => now()->addDays(7),
                'status' => 'pending',
            ]);

            // Hapus dari keranjang
            $item->delete();
        }

        Alert::toast('Permintaan peminjaman dikirim! Menunggu persetujuan admin.', 'success')
            ->position('top-end');

        return redirect()->route('buku.index');
    }

    public function riwayat()
    {
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('riwayat', compact('riwayat'));
    }
}