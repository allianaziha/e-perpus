<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Banner;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        // Buku populer
        $populer = Buku::withCount('peminjamans')
            ->orderBy('peminjamans_count', 'desc')
            ->take(4)
            ->get();

        // Buku terbaru
        $terbaru = Buku::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Banner aktif
        $banners = Banner::where('status', 'aktif')
            ->get(); // ambil semua banner aktif

        $userCount = User::count(); // atau User::count()
        $bukuCount = Buku::count();
        $pinjamCount = Peminjaman::count();


        // Kirim semua data ke view
        return view('welcome', compact('populer', 'terbaru', 'banners', 'userCount', 'bukuCount', 'pinjamCount' ));
    }
}
