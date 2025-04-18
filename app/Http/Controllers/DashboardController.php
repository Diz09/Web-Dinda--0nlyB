<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard pimpinan.
     *
     * @return \Illuminate\View\View
     */
    public function pimpinan()
    {
        // Ambil data yang diperlukan untuk dashboard
        $keuangan = $this->getKeuangan();
        $statistikProduksi = $this->getStatistikProduksi();

        // Ambil data pengeluaran terbaru
    $pengeluaranTerbaru = $this->getPengeluaranTerbaru(); // Pastikan ini diisi

    return view('dashboard.pimpinan', compact('keuangan', 'statistikProduksi', 'pengeluaranTerbaru'));
}

// Fungsi untuk mengambil pengeluaran terbaru
private function getPengeluaranTerbaru()
{
    // Ambil data pengeluaran dari database atau model (contoh menggunakan model Pengeluaran)
    return \App\Models\Pengeluaran::latest()->take(5)->get(); // Sesuaikan dengan model dan data pengeluaran
}
    /**
     * Fungsi untuk mengambil laporan keuangan.
     *
     * @return array
     */
    private function getKeuangan()
    {
        // Ambil data keuangan dari database atau model
        return [
            'pendapatan' => 100000000, // Contoh data pendapatan
            'pengeluaran' => 50000000, // Contoh data pengeluaran
            'laba' => 50000000,       // Contoh data laba
        ];
    }

    /**
     * Fungsi untuk mengambil statistik produksi.
     *
     * @return array
     */
    private function getStatistikProduksi()
    {
        // Ambil data statistik produksi dari database atau model
        return [
            'produk_terjual' => 1000,      // Contoh jumlah produk terjual
            'produk_diproduksi' => 1500,   // Contoh jumlah produk diproduksi
            'stok' => 2000,                // Contoh jumlah stok yang tersedia
        ];
    }
}
