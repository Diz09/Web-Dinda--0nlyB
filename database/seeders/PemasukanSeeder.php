<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasukanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pemasukans')->insert([
            [
                'id_transaksi' => 1,
                'tanggal' => now()->toDateString(),
                'jumlah_masuk' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
