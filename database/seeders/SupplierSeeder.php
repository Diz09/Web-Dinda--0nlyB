<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'nama' => 'Resto canda Kidama',
                'alamat' => 'Jl. Merdeka No.123, Jakarta',
                'no_tlp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Abdi Jaya Kusuma Bali',
                'alamat' => 'Jl. Gatot Subroto No.456, Bali',
                'no_tlp' => '082345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
