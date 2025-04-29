<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $barang1 = Barang::firstWhere('nama_barang', 'Tenggiri');
        $barang2 = Barang::firstWhere('nama_barang', 'Garam');
        $barang3 = Barang::firstWhere('nama_barang', 'Teggiri Kering');

        $supplier1 = Supplier::firstWhere('nama', 'PT. Sumber Pangan');
        $supplier2 = Supplier::firstWhere('nama', 'CV. Berkah Usaha');
        $supplier3 = Supplier::firstWhere('nama', 'Toko Makmur');

        $pemasukan1 = Pemasukan::firstWhere('kode', 'MSK001');
        $pemasukan2 = Pemasukan::firstWhere('kode', 'MSK002');
        $pengeluaran1 = Pengeluaran::firstWhere('kode', 'KLR001');

        Transaksi::create([
            'barang_id' => $barang1->id,
            'supplier_id' => $supplier1->id,
            'pemasukan_id' => $pemasukan1->id,
            'pengeluaran_id' => null,
            'jumlahRp' => 500000,
            'waktu_transaksi' => Carbon::now(),
        ]);

        Transaksi::create([
            'barang_id' => $barang2->id,
            'supplier_id' => $supplier2->id,
            'pemasukan_id' => null,
            'pengeluaran_id' => $pengeluaran1->id,
            'jumlahRp' => 300000,
            'waktu_transaksi' => Carbon::now()->subDays(1),
        ]);

        Transaksi::create([
            'barang_id' => $barang3->id,
            'supplier_id' => $supplier3->id,
            'pemasukan_id' => $pemasukan2->id,
            'pengeluaran_id' => null,
            'jumlahRp' => 700000,
            'waktu_transaksi' => Carbon::now()->subDays(2),
        ]);
    }
}
