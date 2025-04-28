<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangDasar;
use App\Models\BarangMentah;
use App\Models\BarangProduk;
use App\Models\Transaksi;
// use App\Models\BarangMasuk;
// use App\Models\BarangKeluar;

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

        // data dummy grafiks
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'];
        $pendapatanBulanan = [20000000, 25000000, 15000000, 18000000, 22000000];
        $pengeluaranBulanan = [10000000, 12000000, 9000000, 11000000, 8000000];

        return view('dashboard.pimpinan', compact(
            'keuangan',
            'statistikProduksi',
            'pengeluaranTerbaru',
            'labels',
            'pendapatanBulanan',
            'pengeluaranBulanan'
        ));        
    }

    /**
     * Menampilkan dashboard operator.
     */
    public function operator()
    {
        // Ambil 5 data barang terbaru
        $barangTerbaru = Barang::with(['mentah', 'dasar', 'produk'])->latest()->take(5)->get();

        // Ambil 5 transaksi terbaru
        $transaksiTerbaru = Transaksi::with('barang')->latest()->take(5)->get();

        // Jumlah total barang
        $jumlahBarang = Barang::sum('qty');

        return view('dashboard.operator', compact('barangTerbaru', 'transaksiTerbaru', 'jumlahBarang'));
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
