<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard pimpinan.
     */
    public function pimpinan()
    {
        $keuangan = $this->getKeuangan();
        $statistikProduksi = $this->getStatistikProduksi();
        $pengeluaranTerbaru = $this->getPengeluaranTerbaru();

        return view('dashboard.pimpinan', compact('keuangan', 'statistikProduksi', 'pengeluaranTerbaru'));
    }

    /**
     * Menampilkan dashboard operator.
     */
    public function operator()
    {
        $barangMasukTerbaru = BarangMasuk::with('barang')->latest()->take(5)->get();
        $barangKeluarTerbaru = BarangKeluar::with('barang')->latest()->take(5)->get();

        return view('dashboard.operator', compact('barangMasukTerbaru', 'barangKeluarTerbaru'));
    }

    /**
     * Fungsi untuk mengambil pengeluaran terbaru.
     */
    private function getPengeluaranTerbaru()
    {
        return \App\Models\Pengeluaran::latest()->take(5)->get();
    }

    /**
     * Fungsi untuk mengambil laporan keuangan.
     */
    private function getKeuangan()
    {
        return [
            'pendapatan' => 100000000,
            'pengeluaran' => 50000000,
            'laba' => 50000000,
        ];
    }

    /**
     * Fungsi untuk mengambil statistik produksi.
     */
    private function getStatistikProduksi()
    {
        return [
            'produk_terjual' => 1000,
            'produk_diproduksi' => 1500,
            'stok' => 2000,
        ];
    }
}
