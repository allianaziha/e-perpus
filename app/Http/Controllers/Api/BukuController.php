<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // GET /api/buku?search=&kategori=
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->latest()->paginate(12);
        $kategori = Kategori::withCount('bukus')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'buku' => $buku,
                'kategori' => $kategori,
            ]
        ]);
    }

    // GET /api/buku/{id}
    public function detail($id)
    {
        $buku = Buku::findOrFail($id);

        $rekomendasi = Buku::where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $buku->id)
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'buku' => $buku,
                'rekomendasi' => $rekomendasi,
            ]
        ]);
    }

    // GET /api/buku/semua?kategori_id=
    public function semua(Request $request)
    {
        $query = Buku::query();

        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->orderBy('judul', 'asc')->paginate(12);
        $kategori = Kategori::all();

        return response()->json([
            'success' => true,
            'data' => [
                'buku' => $buku,
                'kategori' => $kategori,
            ]
        ]);
    }
}