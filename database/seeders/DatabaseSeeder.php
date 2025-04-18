<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder lain di sini
        $this->call([
            UserSeeder::class,
            // PostSeeder::class,
        ]);
    }
}
