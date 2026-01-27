<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        // SEARCH JUDUL BUKU
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // FILTER KATEGORI
        $selectedCategory = null;
        if ($request->kategori) {
            $selectedCategory = Kategori::find($request->kategori);
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->latest()->paginate(12)->withQueryString();
        $kategori = Kategori::withCount('bukus')->get();

        return view('semua', compact('buku', 'kategori', 'selectedCategory'));
    }

    public function filter($id)
    {
        return redirect()->route('buku.index', ['kategori' => $id]);
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
