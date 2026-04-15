<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartPinjam;
use App\Models\Buku;
use App\Models\Peminjaman;
use Auth;

class ChartPinjamController extends Controller
{
    public function index()
    {
        $chartPinjam = ChartPinjam::with('buku')
            ->where('user_id', auth()->id())
            ->get();

        return view('chart', compact('chartPinjam'));
    }

    public function add(Request $request, $bukuId)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu.'
                ], 401);
            }
            return redirect('/login');
        }

        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok < $request->qty) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku tidak mencukupi.'
            ], 422);
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

        // ✅ INI YANG PENTING
        $cartCount = ChartPinjam::where('user_id', Auth::id())->sum('qty');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan ke keranjang.',
            'count'   => $cartCount
        ]);
    }

    public function update(Request $request, $id)
    {
        $chartItem = ChartPinjam::with('buku')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'qty' => 'required|integer|min:1|max:' . $chartItem->buku->stok,
        ]);

        $chartItem->update(['qty' => $request->qty]);

        return redirect()->route('chart.pinjam.index');
    }

    public function remove($id)
    {
        $chart = ChartPinjam::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $chart->delete();

        // ✅ HITUNG ULANG
        $cartCount = ChartPinjam::where('user_id', auth()->id())->sum('qty');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus dari keranjang.',
                'count'   => $cartCount, // ✅ FIX
            ]);
        }

        return back();
    }

    public function mini()
    {
        $chartPinjam = ChartPinjam::with('buku')
            ->where('user_id', auth()->id())
            ->get();

        $totalBuku = $chartPinjam->sum('qty');

        return view('layouts.components-frontend.minichart-body', compact('chartPinjam', 'totalBuku'))->render();
    }

    public function removeSelected(Request $request)
    {
        $request->validate([
            'selected_items'   => 'required|array',
            'selected_items.*' => 'integer|exists:chart_pinjam,id',
        ]);

        ChartPinjam::where('user_id', auth()->id())
            ->whereIn('id', $request->selected_items)
            ->delete();

        // ✅ HITUNG ULANG
        $cartCount = ChartPinjam::where('user_id', auth()->id())->sum('qty');

        return response()->json([
            'success' => true,
            'message' => 'Item terpilih berhasil dihapus dari keranjang.',
            'count'   => $cartCount, // ✅ FIX
        ]);
    }

    public function checkout()
    {
        $chartPinjam = ChartPinjam::with('buku')
            ->where('user_id', auth()->id())
            ->get();

        if ($chartPinjam->isEmpty()) {
            return redirect()->route('chart.pinjam.index');
        }

        foreach ($chartPinjam as $item) {
            $buku = Buku::find($item->buku_id);
            $buku->stok -= $item->qty;
            $buku->save();

            Peminjaman::create([
                'kode_peminjaman' => 'PJM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
                'buku_id'         => $item->buku_id,
                'user_id'         => auth()->id(),
                'jumlah_buku'     => $item->qty,
                'tgl_pinjam'      => now(),
                'tgl_jatuh_tempo' => now()->addDays(7),
                'status'          => 'pending',
            ]);
        }

        ChartPinjam::where('user_id', auth()->id())->delete();

        return redirect()->route('buku.index');
    }
}