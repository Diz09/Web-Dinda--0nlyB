<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kuartal; 
use Illuminate\Support\Facades\DB;

class KuartalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kuartals')->insert([
            [
                'nama_kuartal' => 'Kuartal-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kuartal' => 'Kuartal-2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);  
    }
}
