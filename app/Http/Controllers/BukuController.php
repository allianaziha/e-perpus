<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::latest()->paginate(12);
        $kategori = Kategori::all();
        return view('semua', compact('buku', 'kategori'));
    }

    public function filter($id)
    {
        $kategori = Kategori::all();
        $selectedCategory = Kategori::findOrFail($id);
        $buku = Buku::where('kategori_id', $id)->paginate(12);

        // Kirim data ke view
        return view('semua', compact('buku', 'kategori', 'selectedCategory'));
    }

    public function detail($id)
    {
        $buku = Buku::findOrFail($id);

        $rekomendasi = Buku::where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $buku->id)
            ->limit(4)
            ->get();

        return view('detail', compact('buku', 'rekomendasi'));
    }

    public function semua(Request $request)
    {
        $query = Buku::query();
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }
        $buku = $query->orderBy('judul', 'asc')->paginate(12);
        $kategori = Kategori::all();
        return view('semua', compact('buku', 'kategori'))->with('filter', 'semua');
    }

}
 