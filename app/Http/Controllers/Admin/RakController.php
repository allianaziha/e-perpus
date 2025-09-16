<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rak;
use Illuminate\Http\Request;
use Alert;

class RakController extends Controller
{
    public function index()
    {
        $rak = Rak::latest()->get();

        $title = 'Hapus Rak!';
        $text  = 'Apakah anda yakin ingin menghapus Rak ini?';
        confirmDelete($title, $text);

        return view('admin.rak.index', compact('rak'));
    } 

    public function create()
    {
        return view('admin.rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'nama' => 'required|string',
            'lokasi' => 'required|string',
        ]);

        $rak = new Rak;   
        $rak->kode = $request->kode;
        $rak->nama = $request->nama;
        $rak->lokasi = $request->lokasi;
        $rak->save();

        toast('Data berhasil disimpan', 'success');
        return redirect()->route('admin.rak.index');
    }

    public function show(Rak $rak)
    {
        return view('admin.rak.show', compact('rak'));
    }

    public function edit(Rak $rak)
    {
        return view('admin.rak.edit', compact('rak'));
    }

    public function update(Request $request, Rak $rak)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'nama' => 'required|string',
            'lokasi' => 'required|string',
        ]);

        $rak->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
        ]);

        Alert::info('Diupdate', 'Rak berhasil diupdate!');
        return redirect()->route('admin.rak.index');
    }

    public function destroy(Rak $rak)
    {
        $rak->delete();
        Alert::error('Dihapus', 'Rak berhasil dihapus!');
        return redirect()->route('admin.rak.index');
    }
}
