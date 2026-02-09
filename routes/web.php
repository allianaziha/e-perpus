<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RakController;
use App\Http\Controllers\Admin\BukuController as AdminBukuController;;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Petugas\BukuController as PetugasBukuController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\Petugas\DendaController as PetugasDendaController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\UserController as PetugasUserController;
use App\Http\Controllers\Petugas\BannerController as PetugasBannerController;
use App\Http\Controllers\User\BukuController as UserBukuController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChartPinjamController;
use App\Http\Controllers\BerandaController;


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('rak', RakController::class);
    Route::resource('buku', AdminBukuController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::patch('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::resource('pengembalian', PengembalianController::class);
    Route::resource('denda', DendaController::class);
    Route::resource('user', UserController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.exportPDF');
    Route::get('laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel');
    Route::resource('banner', BannerController::class);

});

Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    Route::resource('buku', PetugasBukuController::class);
    Route::resource('peminjaman', PetugasPeminjamanController::class);
    Route::patch('peminjaman/{peminjaman}/approve', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('peminjaman/{peminjaman}/reject', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::resource('pengembalian', PetugasPengembalianController::class);
    Route::resource('denda', PetugasDendaController::class);  
    Route::resource('user', PetugasUserController::class);
    Route::resource('banner', PetugasBannerController::class);
});

Route::middleware('auth')->name('user.')->group(function() {
    Route::get('user-buku', [UserBukuController::class, 'index'])->name('buku.index');
    Route::get('user-buku/{buku}', [UserBukuController::class, 'show'])->name('buku.show');
});

// routes/web.php
Route::middleware('auth')->name('user.')->group(function() {
    Route::get('user-buku', [UserBukuController::class, 'index'])->name('buku.index');
    Route::get('user-buku/{buku}', [UserBukuController::class, 'show'])->name('buku.show');
});

Route::middleware(['auth','role:admin,petugas'])->group(function() {
    Route::get('/peminjaman/notifikasi', [PeminjamanController::class, 'notifikasi'])
        ->name('peminjaman.notifikasi');
});

Route::get('/', [BerandaController::class, 'index'])->name('home');

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// Filter berdasarkan kategori
Route::get('/buku/kategori/{id}', [BukuController::class, 'filter'])->name('buku.filter');

// Semua buku (dengan atau tanpa filter kategori)
Route::get('/buku-semua', [BukuController::class, 'semua'])->name('buku.semua');
Route::get('/buku-semua/kategori/{kategori_id}', [BukuController::class, 'semua'])->name('buku.semua.kategori');

// Detail buku
Route::get('/buku/{id}', [BukuController::class, 'detail'])->name('buku.detail');

// ===============================
// Aksi yang butuh login
// ===============================

Route::get('/profile', [ProfileController::class, 'show'])
    ->name('profile.show');

Route::post('/profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');

Route::post('/profile/delete-avatar', [ProfileController::class, 'deleteAvatar'])
    ->name('profile.avatar.delete');

// ChartPinjam
Route::prefix('chart-pinjam')->middleware('auth')->group(function () {
    Route::get('/', [ChartPinjamController::class, 'index'])->name('chart.pinjam.index');
    Route::post('/add/{buku}', [ChartPinjamController::class, 'add'])->name('chart.pinjam.add');
    Route::put('/update/{id}', [ChartPinjamController::class, 'update'])->name('chart.pinjam.update');
    Route::delete('/remove/{id}', [ChartPinjamController::class, 'remove'])->name('chart.pinjam.remove');
    Route::get('/checkout', [ChartPinjamController::class, 'checkout'])->name('chart.pinjam.checkout');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
