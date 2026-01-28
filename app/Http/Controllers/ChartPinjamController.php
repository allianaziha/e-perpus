<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartPinjam;
use App\Models\Buku;
use Auth;
use Alerts;

class ChartPinjamController extends Controller
{
    /**
     * Halaman keranjang peminjaman
     */
    public function index()
    {
        $chartPinjam = ChartPinjam::with('buku')
            ->where('user_id', auth()->id())
            ->get();

        return view('chart', compact('chartPinjam'));
    }

    /**
     * Tambah buku ke keranjang peminjaman
     */
    public function add(Request $request, $bukuId)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
            }
            toast('Silakan login terlebih dahulu.', 'error');
            return redirect('/login');
        }

        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok < $request->qty) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok buku tidak mencukupi.'], 422);
            }
            return back()->with('error', 'Stok buku tidak mencukupi.');
        }

        $chart = ChartPinjam::where('user_id', Auth::id())
            ->where('buku_id', $bukuId)
            ->first();

        if ($chart) {
            $chart->increment('qty', $request->qty);
        } else {
            ChartPinjam::create([
                'user_id' => Auth::id(),
                'buku_id' => $bukuId,
                'qty'     => $request->qty,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Buku berhasil ditambahkan ke keranjang peminjaman.']);
        }

        toast('Buku berhasil ditambahkan ke keranjang peminjaman.', 'success');
        return redirect()->route('chart.pinjam.index');
    }

    /**
     * Update jumlah buku
     */
    public function update(Request $request, $id)
    {
        $chartItem = ChartPinjam::with('buku')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'qty' => 'required|integer|min:1|max:' . $chartItem->buku->stok,
        ]);

        $chartItem->update([
            'qty' => $request->qty,
        ]);

        toast('Jumlah buku berhasil diperbarui.', 'success');
        return redirect()->route('chart.pinjam.index');
    }

    /**
     * Hapus buku dari keranjang
     */
    public function remove($id)
    {
        $chart = ChartPinjam::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $chart->delete();

        toast('Buku berhasil dihapus dari keranjang.', 'success');
        return back();
    }

    /**
     * Proses pinjam buku
     */
    public function checkout()
    {
        $chartPinjam = ChartPinjam::with('buku')
            ->where('user_id', auth()->id())
            ->get();

        if ($chartPinjam->isEmpty()) {
            toast('Keranjang peminjaman kosong.', 'warning');
            return redirect()->route('chart.pinjam.index');
        }

        /**
         * DI SINI NANTI:
         * - simpan ke tabel peminjaman
         * - kurangi stok buku
         */

        foreach ($chartPinjam as $item) {
            $buku = Buku::find($item->buku_id);
            $buku->stok -= $item->qty;
            $buku->save();
        }

        // kosongkan keranjang
        ChartPinjam::where('user_id', auth()->id())->delete();

        toast('Peminjaman berhasil diproses. Permintaan telah dikirim ke admin.', 'success');
        return redirect()->route('buku.index');
    }
}
