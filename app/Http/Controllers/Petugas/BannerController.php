<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Alert;

class BannerController extends Controller
{
    public function index()
    {
        $banner = Banner::latest()->get();

        $title = 'Hapus Banner!';
        $text  = 'Apakah anda yakin ingin menghapus banner ini?';
        confirmDelete($title, $text);

        return view('petugas.banner.index', compact('banner'));
    }

    public function create()
    {
        return view('petugas.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_utama' => 'required|string|max:255',
            'judul'       => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $banner = new Banner();
        $banner->judul_utama = $request->judul_utama;
        $banner->judul       = $request->judul;
        $banner->deskripsi   = $request->deskripsi;
        $banner->status      = $request->status;

        if ($request->hasFile('gambar')) {
            $img = $request->file('gambar');
            $name = rand(1000, 9999) . '_' . $img->getClientOriginalName();
            $img->move(public_path('images/banner'), $name);
            $banner->gambar = $name;
        }

        $banner->save();

        toast('Banner berhasil ditambahkan!', 'success');
        return redirect()->route('petugas.banner.index');
    }

    public function edit(Banner $banner)
    {
        return view('petugas.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'judul_utama' => 'required|string|max:255',
            'judul'       => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        if (!$banner) {
            abort(404, 'Banner tidak ditemukan');
        }

        $banner->update([
            'judul_utama' => $request->judul_utama,
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'status'      => $request->status,
        ]);

        if ($request->hasFile('gambar')) {
            // hapus gambar lama
            if ($banner->gambar && file_exists(public_path('images/banner/' . $banner->gambar))) {
                unlink(public_path('images/banner/' . $banner->gambar));
            }

            $img = $request->file('gambar');
            $name = rand(1000, 9999) . '_' . $img->getClientOriginalName();
            $img->move(public_path('images/banner'), $name);
            $banner->gambar = $name;
            $banner->save();
        }

        Alert::info('Diupdate', 'Banner berhasil diperbarui!');
        return redirect()->route('petugas.banner.index');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->gambar && file_exists(public_path('images/banner/' . $banner->gambar))) {
            unlink(public_path('images/banner/' . $banner->gambar));
        }

        $banner->delete();

        Alert::error('Dihapus', 'Banner berhasil dihapus!');
        return redirect()->route('petugas.banner.index');
    }
}
