<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barang::insert([
            [
                'nama_barang' => 'Tenggiri', 
                'qty' => 100, 
                'exp' => '2025-01-01', 
                'harga' => 9000
            ],
            [
                'nama_barang' => 'Garam', 
                'qty' => 200, 
                'exp' => '2025-06-01', 
                'harga' => 12000
            ],
            [
                'nama_barang' => 'Teggiri Kering', 
                'qty' => 150, 
                'exp' => '2025-03-01', 
                'harga' => 14000
            ],
        ]);        
    }
}
