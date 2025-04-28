<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BarangProduk;

class BarangProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangProduk::insert([
            ['barang_id' => 3, 'kode' => 'PRD001'],
        ]);        
    }
}
