<?php

namespace App\Http\Controllers;

use App\Models\FavoritBuku;
use App\Models\Buku;
use Illuminate\Http\Request;
use Alert;

class FavoritController extends Controller
{
    public function index()
    {
        $favoritBukus = FavoritBuku::with(['buku.rak', 'buku.kategori'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('favorit.index', compact('favoritBukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id'
        ]);

        $userId = auth()->id();
        $bukuId = $request->buku_id;

        // Cek apakah sudah ada di favorit
        $favorit = FavoritBuku::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->first();

        if ($favorit) {
            // Jika sudah ada, hapus (toggle off)
            $favorit->delete();
            return response()->json([
                'message' => 'Buku berhasil dihapus dari favorit',
                'action' => 'removed'
            ]);
        } else {
            // Jika belum ada, tambah (toggle on)
            FavoritBuku::create([
                'user_id' => $userId,
                'buku_id' => $bukuId
            ]);
            return response()->json([
                'message' => 'Buku berhasil ditambahkan ke favorit',
                'action' => 'added'
            ]);
        }
    }

    public function destroy($bukuId)
    {
        $favorit = FavoritBuku::where('user_id', auth()->id())
            ->where('buku_id', $bukuId)
            ->first();

        if (!$favorit) {
            return response()->json(['message' => 'Buku tidak ditemukan di favorit'], 404);
        }

        $favorit->delete();

        return response()->json(['message' => 'Buku berhasil dihapus dari favorit']);
    }
}
