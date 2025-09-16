<?php  

namespace App\Http\Controllers\Petugas;  

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use App\Models\Pengembalian;  
use App\Models\Peminjaman;  
use App\Models\Denda;  
use Carbon\Carbon;  
use Alert;  

class PengembalianController extends Controller  
{  
    public function index()  
    {  
        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.buku', 'denda'])->get();  
        return view('petugas.pengembalian.index', compact('pengembalians'));  
    }  

    public function create()  
    {  
        $peminjamans = Peminjaman::whereDoesntHave('pengembalian')->get();  
        return view('petugas.pengembalian.create', compact('peminjamans'));  
    }  

    public function store(Request $request)  
    {  
        $request->validate([  
            'peminjaman_id' => 'required|exists:peminjamans,id',  
            'tgl_kembali'   => 'required|date',  
            'kondisi'       => 'required|in:baik,rusak,hilang',  
        ]);  

        $pengembalian = Pengembalian::create([  
            'peminjaman_id' => $request->peminjaman_id,  
            'tgl_kembali'   => $request->tgl_kembali,  
            'kondisi'       => $request->kondisi,  
        ]);  

        // ===== Hitung Denda =====
        $peminjaman     = $pengembalian->peminjaman;  
        $tgl_jatuh_tempo= Carbon::parse($peminjaman->tgl_jatuh_tempo);  
        $tgl_kembali    = Carbon::parse($request->tgl_kembali);  

        $jumlah_hari = 0;  
        $total_denda = 0;  
        $jenis = [];  

        if ($tgl_kembali->gt($tgl_jatuh_tempo)) {  
            $jumlah_hari = $tgl_jatuh_tempo->diffInDays($tgl_kembali);  
            $total_denda += $jumlah_hari * 5000;  
            $jenis[] = 'telat';  
        }  

        if ($request->kondisi === 'rusak') {  
            $total_denda += 20000;  
            $jenis[] = 'rusak';  
        } elseif ($request->kondisi === 'hilang') {  
            $total_denda += 20000;  
            $jenis[] = 'hilang';  
        }  

        if ($total_denda > 0) {  
            Denda::create([  
                'pengembalian_id' => $pengembalian->id,  
                'jenis'           => implode(',', $jenis),  
                'jumlah_hari'     => $jumlah_hari > 0 ? $jumlah_hari : null,  
                'nominal'         => $total_denda,  
            ]);  
        }  

        Alert::success('Berhasil', 'Pengembalian berhasil dicatat.');  
        return redirect()->route('petugas.pengembalian.index');  
    }  

    public function show(Pengembalian $pengembalian)  
    {  
        $pengembalian->load(['peminjaman.user', 'peminjaman.buku', 'denda']);  
        return view('petugas.pengembalian.show', compact('pengembalian'));  
    }  

    public function edit(Pengembalian $pengembalian)  
    {  
        $peminjamans = Peminjaman::all();  
        return view('petugas.pengembalian.edit', compact('pengembalian', 'peminjamans'));  
    }  

    public function update(Request $request, Pengembalian $pengembalian)  
    {  
        $request->validate([  
            'tgl_kembali' => 'required|date',  
            'kondisi'     => 'required|in:baik,rusak,hilang',  
        ]);  

        $pengembalian->update([  
            'tgl_kembali' => $request->tgl_kembali,  
            'kondisi'     => $request->kondisi,  
        ]);  

        $pengembalian->denda()->delete();  

        $peminjaman     = $pengembalian->peminjaman;  
        $tgl_jatuh_tempo= Carbon::parse($peminjaman->tgl_jatuh_tempo);  
        $tgl_kembali    = Carbon::parse($request->tgl_kembali);  

        $jumlah_hari = 0;  
        $total_denda = 0;  
        $jenis = [];  

        if ($tgl_kembali->gt($tgl_jatuh_tempo)) {  
            $jumlah_hari = $tgl_jatuh_tempo->diffInDays($tgl_kembali);  
            $total_denda += $jumlah_hari * 5000;  
            $jenis[] = 'telat';  
        }  

        if ($request->kondisi === 'rusak') {  
            $total_denda += 20000;  
            $jenis[] = 'rusak';  
        } elseif ($request->kondisi === 'hilang') {  
            $total_denda += 20000;  
            $jenis[] = 'hilang';  
        }  

        if ($total_denda > 0) {  
            Denda::create([  
                'pengembalian_id' => $pengembalian->id,  
                'jenis'           => implode(',', $jenis),  
                'jumlah_hari'     => $jumlah_hari > 0 ? $jumlah_hari : null,  
                'nominal'         => $total_denda,  
            ]);  
        }  

        Alert::success('Berhasil', 'Pengembalian berhasil diupdate.');  
        return redirect()->route('petugas.pengembalian.index');  
    }  

    public function destroy(Pengembalian $pengembalian)  
    {  
        $pengembalian->denda()->delete();  
        $pengembalian->delete();  

        Alert::success('Berhasil', 'Pengembalian berhasil dihapus.');  
        return redirect()->route('petugas.pengembalian.index');  
    }  
}  
