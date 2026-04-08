<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        return response()->json([
            'success' => true,
            'data' => [
                'populer' => $populer,
                'terbaru' => $terbaru,
                'banners' => $banners,
                'userCount' => $userCount,
                'bukuCount' => $bukuCount,
                'pinjamCount' => $pinjamCount,
            ]
        ]);
    }
}
