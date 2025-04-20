<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataKeuanganController extends Controller
{
    public function index()
    {
        // Data keuangan
        $keuangan = [
            (object)[
                'aktifitas' => 'Penjualan Produk A',
                'jenis' => 'Masuk',
                'jumlah' => 500000,
                'tanggal_transaksi' => '2025-04-20',
            ],
            (object)[
                'aktifitas' => 'Pembelian Bahan Baku',
                'jenis' => 'Keluar',
                'jumlah' => 300000,
                'tanggal_transaksi' => '2025-04-19',
            ],
            (object)[
                'aktifitas' => 'Pembayaran Gaji Karyawan',
                'jenis' => 'Keluar',
                'jumlah' => 200000,
                'tanggal_transaksi' => '2025-04-18',
            ],
            (object)[
                'aktifitas' => 'Penjualan Produk B',
                'jenis' => 'Masuk',
                'jumlah' => 800000,
                'tanggal_transaksi' => '2025-04-17',
            ],
        ];

        // Mengurutkan data berdasarkan tanggal (desc)
        usort($keuangan, function ($a, $b) {
            return strtotime($b->tanggal_transaksi) - strtotime($a->tanggal_transaksi);
        });

        // Kirim data ke view
        return view('pimpinan.laporan_keuangan.index', compact('keuangan'));
    }
}
