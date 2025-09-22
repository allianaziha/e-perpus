<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RakController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Petugas\BukuController as PetugasBukuController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\Petugas\DendaController as PetugasDendaController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('rak', RakController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::patch('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::resource('pengembalian', PengembalianController::class);
    Route::resource('denda', DendaController::class);
    Route::resource('user', UserController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.exportPDF');
    Route::get('laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel');
});

Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    Route::resource('buku', PetugasBukuController::class);
    Route::resource('peminjaman', PetugasPeminjamanController::class);
    Route::patch('peminjaman/{peminjaman}/approve', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('peminjaman/{peminjaman}/reject', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::resource('pengembalian', PetugasPengembalianController::class);
    Route::resource('denda', PetugasDendaController::class);  
});

// routes/web.php
Route::middleware(['auth','role:admin,petugas'])->group(function() {
    Route::get('/peminjaman/notifikasi', [PeminjamanController::class, 'notifikasi'])
        ->name('peminjaman.notifikasi');
});
