<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Alert;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();

        $title = 'Hapus Kategori!';
        $text  = 'Apakah anda yakin ingin menghapus kategori ini?';
        confirmDelete($title, $text);

        return view('admin.kategori.index', compact('kategori'));
    } 

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori =new Kategori ;   
            $kategori->nama = $request->nama;
            $kategori->save();

        toast ('data berhasil disimpan', 'success');
        return redirect()->route('admin.kategori.index');
    }

    public function show(Kategori $kategori)
    {
        return view('admin.kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $kategori->update([
            'nama' => $request->nama
        ]);

        Alert::info('Diupdate', 'Kategori berhasil diupdate!');
        return redirect()->route('admin.kategori.index');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        Alert::error('Dihapus', 'Kategori berhasil dihapus!');
        return redirect()->route('admin.kategori.index');
    }
}
