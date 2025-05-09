<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder lain di sini
        $this->call([
            UserSeeder::class,

            // fungsi presensi dan penggajian
            KaryawanSeeder::class,
            KuartalSeeder::class,
            TonIkanSeeder::class,
            PresensiSeeder::class,

            // Gudang Barang
            BarangSeeder::class,
            BarangDasarSeeder::class,
            BarangMentahSeeder::class,
            BarangProdukSeeder::class,

            // Suppliers
            SupplierSeeder::class,
            KonsumenSeeder::class,
            PemasokSeeder::class,

            // Transaksi
            PengeluaranSeeder::class,
            PemasukanSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
