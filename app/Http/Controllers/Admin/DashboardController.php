<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Rak;
use App\Models\Peminjaman;
use App\Models\Pengembalian; // tambahkan ini
use App\Models\Denda;
use App\models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalRak = Rak::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalDenda = Denda::sum('nominal');
        $totalPengembalian = Pengembalian::count();
        $totalUser = User::count();

        // Ambil bulan sekarang
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $mingguLabels = [];
        $peminjamanMingguan = [];
        $pengembalianMingguan = []; // array untuk pengembalian mingguan
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

            // Geser ke minggu berikutnya
            $start->addWeek();
            $i++;
        }

        return view('admin.dashboard', compact(
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
