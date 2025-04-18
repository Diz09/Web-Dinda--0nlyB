<?php
// app/Http/Controllers/PresensiController.php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Gaji;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $presensis = Presensi::with('karyawan', 'gaji')->latest()->get();
        return view('operator.presensi.index', compact('presensis'));
    }

    public function create()
    {
        $karyawans = Karyawan::all();
        return view('operator.presensi.create', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required|after:jam_masuk',
        ]);

        $presensi = Presensi::create($request->only([
            'karyawan_id', 'tanggal', 'jam_masuk', 'jam_pulang'
        ]));

        // Hitung total jam
        $jamMasuk = Carbon::parse($request->jam_masuk);
        $jamPulang = Carbon::parse($request->jam_pulang);
        $totalJam = $jamMasuk->diffInMinutes($jamPulang) / 60;
        $lembur = max(0, $totalJam - 8);
        $normal = min(8, $totalJam);

        $karyawan = Karyawan::find($request->karyawan_id);
        $gajiPokok = $normal * $karyawan->gaji_per_jam;
        $gajiLembur = $lembur * ($karyawan->gaji_per_jam * 1.5); // lembur 1.5x
        $totalGaji = $gajiPokok + $gajiLembur;

        Gaji::create([
            'presensi_id' => $presensi->id,
            'total_jam' => $totalJam,
            'jam_lembur' => $lembur,
            'gaji_pokok' => $gajiPokok,
            'gaji_lembur' => $gajiLembur,
            'total_gaji' => $totalGaji,
        ]);

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil disimpan.');
    }
}
