<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'pimpinan',
                'email' => 'pimpinan@example.com',
                'password' => '$2y$12$BxfzmiE7LIHKu7/IatOV.nGYdLXVixT/L4Bmps2xljJNhwfqf9Lq', // password123
                'role' => 'pimpinan',
            ],
            [
                'name' => 'operator',
                'email' => 'operator@example.com',
                'password' => '$2y$12$BxfzmiE7LIHKu7/IatOV.nGYdLXVixT/L4Bmps2xljJNhwfqf9Lq', // password123 juga
                'role' => 'operator',
            ],
        ]);
    }
}
