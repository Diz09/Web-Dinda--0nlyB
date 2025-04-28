<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transaksis')->insert([
            [
                'barang_id' => 1,
                'supplier_id' => 1,
                'kategori' => 'pemasukan',
                'waktu_transaksi' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 2,
                'supplier_id' => 2,
                'kategori' => 'pengeluaran',
                'waktu_transaksi' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
