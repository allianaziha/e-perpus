<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Rak;
use App\Models\Kategori;
use Alert;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::latest()->get();

        $title = 'Hapus Buku!';
        $text  = 'Apakah anda yakin ingin menghapus buku ini?';
        confirmDelete($title, $text);

        return view('petugas.buku.index', compact('buku'));
    }

    public function create()
    {
        $rak = Rak::all();
        $kategori = Kategori::all();
        return view('petugas.buku.create', compact('rak', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku'    => 'required|string|max:255',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'stok'         => 'required|integer',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'rak_id'       => 'required|exists:raks,id',
            'kategori_id'  => 'required|exists:kategoris,id',
        ]);

        $buku = new Buku;
        $buku->kode_buku    = $request->kode_buku;
        $buku->judul        = $request->judul;
        $buku->penulis      = $request->penulis;
        $buku->penerbit     = $request->penerbit;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->stok         = $request->stok;
        $buku->deskripsi    = $request->deskripsi;
        $buku->rak_id       = $request->rak_id;
        $buku->kategori_id  = $request->kategori_id;

        if ($request->hasFile('gambar')) {
            $img = $request->file('gambar');
            $name = rand(1000,9999).'_'.$img->getClientOriginalName();
            $img->move(public_path('images/buku'), $name);
            $buku->gambar = $name;
        }

        $buku->save();

        toast('Data berhasil disimpan', 'success');
        return redirect()->route('petugas.buku.index');
    }

    public function show(Buku $buku)
    {
        return view('petugas.buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $rak = Rak::all();
        $kategori = Kategori::all();
        return view('petugas.buku.edit', compact('buku', 'rak', 'kategori'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode_buku'    => 'required|string|max:255',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'stok'         => 'required|integer',
            'deskripsi'   => 'nullable|string',
            'gambar'       => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'rak_id'       => 'required|exists:raks,id',
            'kategori_id'  => 'required|exists:kategoris,id',
        ]);

        $buku->update([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => $request->stok,
            'deskripsi'    => $request->deskripsi,
            'rak_id'       => $request->rak_id,
            'kategori_id'  => $request->kategori_id,
        ]);

        if ($request->hasFile('gambar')) {
            if ($buku->gambar && file_exists(public_path('images/buku/'.$buku->gambar))) {
                unlink(public_path('images/buku/'.$buku->gambar));
            }
            $img = $request->file('gambar');
            $name = rand(1000,9999).'_'.$img->getClientOriginalName();
            $img->move(public_path('images/buku'), $name);
            $buku->gambar = $name;
            $buku->save();
        }

        Alert::info('Diupdate', 'Buku berhasil diupdate!');
        return redirect()->route('petugas.buku.index');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->gambar && file_exists(public_path('images/buku/'.$buku->gambar))) {
            unlink(public_path('images/buku/'.$buku->gambar));
        }
        $buku->delete();

        Alert::error('Dihapus', 'Buku berhasil dihapus!');
        return redirect()->route('petugas.buku.index');
    }
}
