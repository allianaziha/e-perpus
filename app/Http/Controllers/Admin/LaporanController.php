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
        $status = $request->status ?? '';
        $tab    = $request->tab ?? 'buku';

        $data = $this->getFilteredData($awal, $akhir, $status);

        // Kirim juga tab, awal, akhir
        $data['tab']   = $tab;
        $data['awal']  = $awal;
        $data['akhir'] = $akhir;
        $data['status']= $status;

        return view('admin.laporan.index', $data);
    }

    // Export PDF
    public function exportPDF(Request $request)
    {
        $awal   = $request->awal;
        $akhir  = $request->akhir;
        $status = $request->status ?? '';
        $tab    = $request->tab ?? 'buku';

        $data = $this->getFilteredData($awal, $akhir, $status);

        // Kirim tab & tanggal juga ke blade PDF
        $data['tab']   = $tab;
        $data['awal']  = $awal;
        $data['akhir'] = $akhir;
        $data['status']= $status;

        $pdf = Pdf::loadView('admin.laporan.rekap', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download("laporan-{$tab}.pdf");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $awal   = $request->awal;
        $akhir  = $request->akhir;
        $status = $request->status ?? '';
        $tab    = $request->tab ?? 'buku';

        $data = $this->getFilteredData($awal, $akhir, $status);

        // Tambahkan info tab & tanggal
        $data['tab']   = $tab;
        $data['awal']  = $awal;
        $data['akhir'] = $akhir;
        $data['status']= $status;

        return Excel::download(new LaporanExport($data, $tab), "laporan-{$tab}.xlsx");
    }

    // Ambil data sesuai filter
    private function getFilteredData($awal, $akhir, $status)
    {
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
        if ($status === 'dipinjam') {
            $peminjaman->whereDoesntHave('pengembalian');
        } elseif ($status === 'dikembalikan') {
            $peminjaman->whereHas('pengembalian', function($q) use ($awalDate, $akhirDate){
                if ($awalDate && $akhirDate) {
                    $q->whereBetween('tgl_kembali', [$awalDate, $akhirDate]);
                }
            });
        }
        $peminjaman = $peminjaman->get();

        // ==================== Pengembalian ====================
        $pengembalian = Pengembalian::with(['peminjaman.user','peminjaman.buku']);
        if ($awalDate && $akhirDate) {
            $pengembalian->whereBetween('tgl_kembali', [$awalDate, $akhirDate]);
        }
        $pengembalian = $pengembalian->get();

        // ==================== Denda ====================
        $denda = Denda::with(['pengembalian.peminjaman.user','pengembalian.peminjaman.buku']);
        if ($awalDate && $akhirDate) {
            $denda->whereHas('pengembalian', function($q) use ($awalDate, $akhirDate) {
                $q->whereBetween('tgl_kembali', [$awalDate, $akhirDate]);
            });
        }
        if ($status === 'lunas') {
            $denda->where('status', 'Lunas');
        } elseif ($status === 'belum_lunas') {
            $denda->where('status', 'Belum Lunas');
        }
        $denda = $denda->get();

        // ==================== Rekap Denda ====================
        $totalSemua      = $denda->sum('nominal');
        $totalSudahBayar = $denda->sum('dibayar');
        $totalSisa       = $totalSemua - $totalSudahBayar;
        $totalLunas      = $denda->where('status','Lunas')->sum('nominal');
        $totalBelum      = $denda->where('status','Belum Lunas')->sum('nominal');

        return compact('buku','peminjaman','pengembalian','denda',
                       'totalSemua','totalSudahBayar','totalSisa','totalLunas','totalBelum');
    }
}
