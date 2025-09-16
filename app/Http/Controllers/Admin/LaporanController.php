<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Denda;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    // Halaman laporan
    public function index(Request $request)
    {
        $awal   = $request->awal;
        $akhir  = $request->akhir;
        $status = $request->status ?? 'semua'; // default semua
        $tab    = $request->tab ?? 'buku';     // default tab Buku

        $data = $this->getFilteredData($request);

        return view('admin.laporan.index', array_merge($data, [
            'awal'   => $awal,
            'akhir'  => $akhir,
            'status' => $status,
            'tab'    => $tab,
        ]));
    }

    // Export PDF (hanya tab aktif)
    public function exportPDF(Request $request)
    {
        $data = $this->getFilteredData($request);
        $tab  = $request->tab ?? 'buku';

        $pdf = Pdf::loadView('admin.laporan.pdf', array_merge($data, ['tab' => $tab]))
                  ->setPaper('a4','portrait');

        return $pdf->download("laporan-{$tab}.pdf");
    }

    // Export Excel (hanya tab aktif)
    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request);
        $tab  = $request->tab ?? 'buku';

        return Excel::download(new LaporanExport($data, $tab), "laporan-{$tab}.xlsx");
    }

    // Ambil data sesuai filter (PDF/Excel)
    private function getFilteredData($request)
    {
        $awal   = $request->awal;
        $akhir  = $request->akhir;
        $status = $request->status ?? 'semua';

        // Format tanggal untuk query
        $awalDate  = $awal ? $awal . ' 00:00:00' : null;
        $akhirDate = $akhir ? $akhir . ' 23:59:59' : null;

        // ==================== Buku ====================
        $buku = Buku::query();
        if ($awalDate && $akhirDate) {
            $buku->whereBetween('created_at', [$awalDate, $akhirDate]);
        }
        $buku = $buku->get();

        // ==================== Peminjaman ====================
        $peminjaman = Peminjaman::with(['buku','user','pengembalian']);
        if ($awalDate && $akhirDate) {
            $peminjaman->whereBetween('tgl_pinjam', [$awalDate, $akhirDate]);
        }

        // Filter status peminjaman
        if ($status === 'dikembalikan') {
            $peminjaman->whereHas('pengembalian', function($q) use ($awalDate, $akhirDate) {
                if ($awalDate && $akhirDate) {
                    $q->whereBetween('tgl_kembali', [$awalDate, $akhirDate]);
                }
            });
        } elseif ($status === 'dipinjam') {
            $peminjaman->whereDoesntHave('pengembalian');
        }

        $peminjaman = $peminjaman->get();

        // ==================== Pengembalian ====================
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.buku']);
        if ($awalDate && $akhirDate) {
            $pengembalian->whereBetween('tgl_kembali', [$awalDate, $akhirDate]);
        }
        $pengembalian = $pengembalian->get();

        // ==================== Denda ====================
        $denda = Denda::with(['pengembalian.peminjaman.user', 'pengembalian.peminjaman.buku']);
        if ($awalDate && $akhirDate) {
            $denda->whereBetween('created_at', [$awalDate, $akhirDate]);
        }
        if (strtolower($status) === 'lunas') {
            $denda->where('status', 'Lunas');
        } elseif (strtolower($status) === 'belum_lunas') {
            $denda->where('status', 'Belum Lunas');
        }
        $denda = $denda->get();

        // ==================== Rekap Denda ====================
        $totalSemua      = $denda->sum('nominal');
        $totalSudahBayar = $denda->sum('dibayar');
        $totalSisa       = $totalSemua - $totalSudahBayar;
        $totalLunas      = $denda->where('status', 'Lunas')->sum('nominal');
        $totalBelum      = $denda->where('status', 'Belum Lunas')->sum('nominal');

        return compact(
            'buku', 'peminjaman', 'pengembalian', 'denda',
            'totalSemua','totalSudahBayar','totalSisa','totalLunas','totalBelum'
        );
    }
}
