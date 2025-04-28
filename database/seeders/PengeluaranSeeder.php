<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengeluarans')->insert([
            [
                'id_transaksi' => 2,
                'tanggal' => now()->toDateString(),
                'jumlah_keluar' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
