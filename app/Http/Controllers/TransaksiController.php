<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
// use App\Models\Barang;
// use App\Models\Supplier;
// use App\Models\Pemasukan;
// use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['barang.mentah', 'barang.dasar', 'barang.produk', 'supplier', 'pemasukan', 'pengeluaran'])
            ->orderBy('waktu_transaksi')
            ->get();

        $totalSebelumnya = 0;

        $data = $transaksis->map(function ($trx) use (&$totalSebelumnya) {
            $kodeBarang = $trx->barang->mentah->kode ?? $trx->barang->dasar->kode ?? $trx->barang->produk->kode ?? '-';
            $masuk = $trx->pemasukan_id ? $trx->jumlahRp : 0;
            $keluar = $trx->pengeluaran_id ? $trx->jumlahRp : 0;

            $totalSekarang = $totalSebelumnya + $masuk - $keluar;
            $totalSebelumnya = $totalSekarang;

            return [
                'waktu' => $trx->waktu_transaksi,
                'kode_transaksi' => $trx->pemasukan->kode ?? $trx->pengeluaran->kode ?? '-',
                'kode_barang' => $kodeBarang,
                'supplier' => $trx->supplier->nama ?? '-',
                'nama_barang' => $trx->barang->nama_barang,
                'qty' => $trx->barang->qty ?? 0,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'total' => $totalSekarang,
            ];
        });

        return view('operator.transaksi.index', compact('data'));
    }
}
