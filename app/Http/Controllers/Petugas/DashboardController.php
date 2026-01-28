<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Rak;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Denda;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik atas
        $totalBuku = Buku::count();
        $totalRak = Rak::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalPengembalian = Pengembalian::count();
        $totalDenda = Denda::sum('nominal');
        $totalUser = User::where('role', 'user')->count();

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
            'totalRak',
            'totalDipinjam',
            'totalPengembalian',
            'totalDenda',
            'totalUser',
            'mingguLabels',
            'peminjamanMingguan',
            'pengembalianMingguan',
            'dendaMingguan'
        ));
    }
}
