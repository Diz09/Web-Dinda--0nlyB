<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;

class LaporanKaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::all();

        // Hitung total gaji untuk setiap karyawan
        foreach ($karyawan as $data) {
            $gaji_per_jam = 10000;      // contoh: Rp 10.000 per jam kerja
            $gaji_lembur_per_jam = 15000; // contoh: Rp 15.000 per jam lembur

            $data->total_gaji = ($data->jam_kerja * $gaji_per_jam) + ($data->jam_lembur * $gaji_lembur_per_jam);
        }

        return view('pimpinan.laporan_karyawan.index', compact('karyawan'));
    }
}
