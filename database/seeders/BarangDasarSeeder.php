<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BarangDasar;

class BarangDasarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangDasar::insert([
            ['barang_id' => 2, 'kode' => 'DSR001'],
        ]);        
    }
}
