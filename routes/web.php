<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\gajiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanKaryawanController;
use App\Http\Controllers\DataKeuanganController;
use App\Http\Controllers\TonIkanController;
use App\Http\Controllers\TransaksiController;

// Route::get('/home', function () {
//     return view('welcome');
// })->name('home');


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
    Route::get('/pimpinan/laporan-karyawan', [LaporanKaryawanController::class, 'index'])->name('pimpinan.laporan_karyawan.index');
    Route::get('/pimpinan/stok-barang', [BarangController::class, 'stokPimpinan'])->name('pimpinan.stock_barang.index');
    Route::get('/pimpinan/laporan-keuangan', [DataKeuanganController::class, 'index'])->name('pimpinan.laporan_keuangan.index');
// });

// Operator
// Route::middleware(['auth', 'role:operator'])->group(function () {
    // Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->name('dashboard.operator');
    // Route::get('/dashboard-operator', [DashboardController::class, 'operator'])->middleware('auth');
    Route::get('/operator/dashboard', [DashboardController::class, 'operator'])->name('dashboard.operator');
    
    Route::get('/operator/barang', [BarangController::class, 'index'])->name('operator.barang.index');
    // // Route::get('/operator/barang/create', [BarangController::class, 'create'])->name('barang.create');
    // // Route::post('/operator/barang', [BarangController::class, 'store'])->name('barang.store');
    // // Route::get('/operator/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    // // Route::put('/operator/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    // // Route::delete('/operator/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // // Route::post('/barang/{id}/tambah', [BarangController::class, 'tambahStok'])->name('barang.tambah');
    // // Route::post('/barang/{id}/kurang', [BarangController::class, 'kurangStok'])->name('barang.kurang');


    // Route::get('/operator/barang-masuk', [BarangMasukController::class, 'index'])->name('barangmasuk.index'); 
    // Route::get('/operator/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barangmasuk.create');
    // Route::post('/operator/barang-masuk', [BarangMasukController::class, 'store'])->name('barangmasuk.store');
    
    // Route::get('/operator/barang-keluar', [BarangKeluarController::class, 'index'])->name('barangkeluar.index'); 
    // Route::get('/operator/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barangkeluar.create');
    // Route::post('/operator/barang-keluar', [BarangKeluarController::class, 'store'])->name('barangkeluar.store');

    Route::get('/operator/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/operator/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/operator/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    
    Route::post('/presensi/{id}/masuk', [PresensiController::class, 'inputMasuk'])->name('presensi.masuk');
    Route::post('/presensi/{id}/pulang', [PresensiController::class, 'inputPulang'])->name('presensi.pulang');
    
    Route::get('/operator/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/operator/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/operator/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/operator/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/operator/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/operator/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    
    Route::post('/operator/karyawan/{id}/gaji-bayar', [KaryawanController::class, 'gajiLunas'])->name('karyawan.gaji.bayar');
    
    Route::post('/operator/tonikan', [TonIkanController::class, 'store'])->name('tonikan.store');
    Route::post('/operator/tonikan', [TonIkanController::class, 'store'])->name('tonikan.store');
    
    Route::post('/operator/gaji/{id}/lunas', [GajiController::class, 'bayar'])->name('gaji.lunas');

    Route::get('/operator/gaji', [GajiController::class, 'index'])->name('gaji.kuartal');
    Route::get('/operator/gaji/{id}', [GajiController::class, 'detail'])->name('gaji.kuartal');
    // Route::get('/operator/gaji', [GajiController::class, 'detail'])->name('gaji.kuartal.detail');
    
    // Route::get('/operator/rekap-gaji', [GajiController::class, 'rekap'])->name('gaji.rekap');

    Route::post('/presensi/tonikan/store', [PresensiController::class, 'simpanTonIkan'])->name('presensi.tonikan.store');


    // Route::get('/operator/suplier', function () {    return view('operator.supplier.index');    })->name('suplier');
    Route::get('/operator/suplier', [SupplierController::class, 'index'])->name('operator.supplier.index');
    
    // Route::resource('transaksi', TransaksiController::class);
    Route::get('/operator/transaksi', [TransaksiController::class, 'index'])->name('operator.transaksi.index');
    Route::get('/operator/transaksi/create', [TransaksiController::class, 'create'])->name('operator.transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('operator.transaksi.store');

    Route::get('/operator/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('operator.transaksi.edit');
    Route::delete('/operator/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('operator.transaksi.destroy');

    // Route::get('/operator/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/operator/transaksi/{id}', [TransaksiController::class, 'update'])->name('operator.transaksi.update');
    // Route::delete('/operator/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');


// });

