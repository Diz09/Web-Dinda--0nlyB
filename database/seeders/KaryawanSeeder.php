<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'Andi',     'jenis_kelamin' => 'L'],
            ['nama' => 'Budi',     'jenis_kelamin' => 'L'],
            ['nama' => 'Citra',    'jenis_kelamin' => 'P'],
            ['nama' => 'Dewi',     'jenis_kelamin' => 'P'],
            ['nama' => 'Eka',      'jenis_kelamin' => 'L'],
            ['nama' => 'Fajar',    'jenis_kelamin' => 'L'],
            ['nama' => 'Gita',     'jenis_kelamin' => 'P'],
            ['nama' => 'Hana',     'jenis_kelamin' => 'P'],
            ['nama' => 'Iwan',     'jenis_kelamin' => 'L'],
            ['nama' => 'Joko',     'jenis_kelamin' => 'L'],
        ];

        foreach ($data as $item) {
            DB::table('karyawans')->insert([
                'nama' => $item['nama'],
                'jenis_kelamin' => $item['jenis_kelamin'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
