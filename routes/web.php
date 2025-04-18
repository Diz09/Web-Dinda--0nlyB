<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;

// Rute untuk login dan logout
Route::get('/login', function () {
    return view('login'); // pastikan file ini ada di resources/views/auth/login.blade.php
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

// Rute untuk dashboard pimpinan
Route::get('/dashboard-pimpinan', [DashboardController::class, 'pimpinan'])->middleware('auth');

// Rute untuk dashboard operator
Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->middleware('auth');

// Rute untuk pengeluaran
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index')->middleware('auth');

// Rute untuk absen
Route::prefix('absen')->group(function () {
    Route::get('/', [AbsenController::class, 'index'])->name('dashboard.absen');
    Route::get('/create', [AbsenController::class, 'create'])->name('absen.create');
    Route::post('/', [AbsenController::class, 'store'])->name('absen.store');
    Route::get('/{id}/edit', [AbsenController::class, 'edit'])->name('absen.edit');
    Route::put('/{id}', [AbsenController::class, 'update'])->name('absen.update');
    Route::delete('/{id}', [AbsenController::class, 'destroy'])->name('absen.destroy');
});

// Rute untuk barang dengan middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barangmasuk.index'); 
    Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barangmasuk.create');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barangmasuk.store');
});
