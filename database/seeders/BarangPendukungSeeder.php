<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BarangPendukung;

class BarangMentahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangPendukung::insert([
            ['barang_id' => 1, 'kode' => 'MNT001'],
        ]);
    }
}
