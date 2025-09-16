<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Denda;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik atas
        $totalBuku = Buku::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalPengembalian = Pengembalian::count();
        $totalDenda = Denda::sum('nominal');

        // Grafik mingguan
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $mingguLabels = [];
        $peminjamanMingguan = [];
        $pengembalianMingguan = [];
        $dendaMingguan = [];

        $start = $startOfMonth->copy();
        $i = 1;

        while ($start->lt($endOfMonth)) {
            $end = $start->copy()->endOfWeek();

            // Label Minggu ke-i
            $mingguLabels[] = "Minggu " . $i;

            // Hitung jumlah peminjaman per minggu
            $peminjamanMingguan[] = Peminjaman::whereBetween('tgl_pinjam', [$start, $end])->count();

            // Hitung jumlah pengembalian per minggu
            $pengembalianMingguan[] = Pengembalian::whereBetween('tgl_kembali', [$start, $end])->count();

            // Hitung total denda per minggu
            $dendaMingguan[] = Denda::whereBetween('created_at', [$start, $end])->sum('nominal');

            $start->addWeek();
            $i++;
        }

        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalDipinjam',
            'totalPengembalian',
            'totalDenda',
            'mingguLabels',
            'peminjamanMingguan',
            'pengembalianMingguan',
            'dendaMingguan'
        ));
    }
}
