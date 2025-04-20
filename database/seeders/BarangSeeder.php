<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('barangs')->insert([
                'kode' => 'BRG' . str_pad($i, 3, '0', STR_PAD_LEFT), // BRG001, BRG002, ...
                'nama' => 'Barang ' . $i,
                'kategori' => ['Elektronik', 'Pakaian', 'Makanan'][rand(0, 2)],
                'harga' => rand(10000, 100000),
                'stok' => rand(1, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
