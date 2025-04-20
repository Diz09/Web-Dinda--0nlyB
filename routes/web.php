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
// Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    // Route::get('/dashboard-pimpinan', [DashboardController::class, 'pimpinan'])->middleware('auth');
    Route::get('/pimpinan/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard.pimpinan');
    Route::get('/pimpinan/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
// });

// tampilan dashboard
// Laporan
// laporankaryawan
// laporan stockbarang
// laporanpengeluaran
// laporan pendapantan

// Operator
// Route::middleware(['auth', 'role:operator'])->group(function () {
    // Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->name('dashboard.operator');
    // Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->middleware('auth');
    Route::get('/operator/dashboard', [DashboardController::class, 'operator'])->name('dashboard.operator');
    
    Route::get('/operator/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/operator/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/operator/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/operator/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/operator/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/operator/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('/operator/barang-masuk', [BarangMasukController::class, 'index'])->name('barangmasuk.index'); 
    Route::get('/operator/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barangmasuk.create');
    Route::post('/operator/barang-masuk', [BarangMasukController::class, 'store'])->name('barangmasuk.store');
    
    Route::get('/operator/barang-keluar', [BarangKeluarController::class, 'index'])->name('barangkeluar.index'); 
    Route::get('/operator/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barangkeluar.create');
    Route::post('/operator/barang-keluar', [BarangKeluarController::class, 'store'])->name('barangkeluar.store');

    Route::get('/operator/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/operator/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/operator/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    
    Route::get('/operator/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/operator/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/operator/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/operator/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/operator/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/operator/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
// });

// Presensi & gaji
// Route::middleware(['auth'])->group(function () {
//     Route::get('/gaji', [gajiController::class, 'index'])->name('gaji.index'); 
//     // Route::get('/gaji/create', [gajiController::class, 'create'])->name('gaji.create');
//     Route::post('/gaji', [gajiController::class, 'store'])->name('gaji.store');
// });
