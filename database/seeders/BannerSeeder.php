<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'judul_utama' => 'Pinjam Buku Lebih Mudah',
            'judul' => 'Dengan E-perpus',
            'deskripsi' => 'E-perpus memudahkan siswa dan masyarakat untuk mencari, meminjam, serta mengelola pengembalian buku secara praktis dan cepat.',
            'gambar' => 'images/banner/perpus.png',
            'status' => 'aktif',
        ]);
    }
}
