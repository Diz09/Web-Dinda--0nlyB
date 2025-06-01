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
use App\Http\Controllers\LaporanController;
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
    Route::get('/pimpinan/laporan-barang', [LaporanController::class, 'barang'])->name('laporan.barang');
    Route::get('/pimpinan/laporan-karyawan', [LaporanController::class, 'karyawan'])->name('laporan.karyawan');
    Route::get('/pimpinan/laporan-supplier', [LaporanController::class, 'supplier'])->name('laporan.supplier');
    Route::get('/pimpinan/laporan-transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');

// });

// Operator
// Route::middleware(['auth', 'role:operator'])->group(function () {
    // Route untuk Dashboard
    Route::get('/operator/dashboard', [DashboardController::class, 'operator'])->name('dashboard.operator');
    
    // route untuk data barang
    Route::get('/operator/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/operator/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/operator/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/operator/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/operator/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/operator/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::patch('/operator/barang/{id}/update-qty', [BarangController::class, 'updateQty'])->name('barang.updateQty');
    Route::get('/barang/check', [BarangController::class, 'check'])->name('barang.check');

    
    // route untuk data supplier
    Route::get('/operator/suplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/operator/suplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/operator/suplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/operator/suplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/operator/suplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/operator/suplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    
    // route untuk data karyawan
    Route::get('/operator/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/operator/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/operator/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/operator/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/operator/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/operator/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    
    Route::post('/operator/karyawan/{id}/gaji-bayar', [KaryawanController::class, 'gajiLunas'])->name('karyawan.gaji.bayar');
    
    // route untuk tindak presensi
    Route::post('/presensi/pilih-karyawan', [PresensiController::class, 'pilihKaryawan'])->name('presensi.pilih-karyawan');

    Route::get('/operator/presensi/{kloter_id}', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/operator/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/operator/presensi', [PresensiController::class, 'store'])->name('presensi.store');


    Route::post('/operator/gaji/{kloter_id}', [PresensiController::class, 'store'])->name('presensi.gaji.store');
    
    Route::post('/presensi/{id}/masuk', [PresensiController::class, 'inputMasuk'])->name('presensi.masuk');
    Route::post('/presensi/{id}/pulang', [PresensiController::class, 'inputPulang'])->name('presensi.pulang');
    
    Route::post('/presensi/tonikan/store', [PresensiController::class, 'simpanTonIkan'])->name('presensi.tonikan.store');
    // Route::post('/presensi/tonikan/store', [PresensiController::class, 'simpanTonIkan'])->name('presensi.tonikan.store');

    // route untuk data gaji per kloter
    Route::post('/operator/gaji/{id}/lunas', [GajiController::class, 'bayar'])->name('gaji.lunas');

    Route::get('/operator/gaji', [GajiController::class, 'index'])->name('gaji.kloter');
    Route::get('/operator/gaji/{id}', [GajiController::class, 'detail'])->name('gaji.kloter.detail');

    Route::post('/gaji/kloter/{id}/selesai', [GajiController::class, 'kloterSelesai'])->name('gaji.kloter.selesai');

    // route untuk transaksi
    Route::get('/operator/transaksi', [TransaksiController::class, 'index'])->name('operator.transaksi.index');
    Route::get('/operator/transaksi/create', [TransaksiController::class, 'create'])->name('operator.transaksi.create');
    Route::post('/operator/transaksi', [TransaksiController::class, 'store'])->name('operator.transaksi.store');
    Route::get('/operator/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('operator.transaksi.edit');
    Route::delete('/operator/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('operator.transaksi.destroy');
    Route::put('/operator/transaksi/{id}', [TransaksiController::class, 'update'])->name('operator.transaksi.update');

    Route::get('/operator/get-barang', function (Request $request) {    // Mengambil kategori dari request
        $kategori = $request->input('kategori', 'pemasukan');
        $tipe = $kategori === 'pengeluaran' ? 'pendukung' : 'produk';

        $barangs = Barang::with($tipe)->whereHas($tipe)->get();

        return response()->json($barangs);
    });
// });

