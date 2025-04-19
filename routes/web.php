<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\gajiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;

// Rute untuk login dan logout
Route::get('/', function () {
    return view('login'); // pastikan file ini ada di resources/views/auth/login.blade.php
})->name('login');

Route::post('/', [AuthenticatedSessionController::class, 'store']);
// Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Pimpinan
Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    // Route::get('/dashboard-pimpinan', [DashboardController::class, 'pimpinan'])->middleware('auth');
    Route::get('/dashboard-pimpinan', [DashboardController::class, 'pimpinan'])->name('dashboard.pimpinan');
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
});

// tampilan dashboard
// Laporan
// laporankaryawan
// laporan stockbarang
// laporanpengeluaran
// laporan pendapantan

// Operator
Route::middleware(['auth', 'role:operator'])->group(function () {
    // Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->name('dashboard.operator');
    // Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->middleware('auth');
    Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->name('dashboard.operator');
    
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barangmasuk.index'); 
    Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barangmasuk.create');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barangmasuk.store');
    
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barangkeluar.index'); 
    Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barangkeluar.create');
    Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barangkeluar.store');

    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
});

// Presensi & gaji
// Route::middleware(['auth'])->group(function () {
//     Route::get('/gaji', [gajiController::class, 'index'])->name('gaji.index'); 
//     // Route::get('/gaji/create', [gajiController::class, 'create'])->name('gaji.create');
//     Route::post('/gaji', [gajiController::class, 'store'])->name('gaji.store');
// });
