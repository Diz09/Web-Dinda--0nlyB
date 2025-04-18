<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;

class AbsenController extends Controller
{
    // Menampilkan semua data absen
    public function index()
    {
        $absens = Absen::orderBy('tanggal', 'desc')->get();
        return view('dashboard.absen', compact('absens'));
    }

    // Menampilkan form tambah absen
    public function create()
    {
        return view('absen.create');
    }

    // Menyimpan data absen baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
            'gaji_per_jam' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $jamMasuk = strtotime($request->jam_masuk);
        $jamPulang = strtotime($request->jam_pulang);
        $totalJam = round(($jamPulang - $jamMasuk) / 3600, 2);
        $gaji = $totalJam * $request->gaji_per_jam;

        Absen::create([
            'nama' => $request->nama,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'total_jam' => $totalJam,
            'gaji_per_jam' => $request->gaji_per_jam,
            'gaji' => $gaji,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('absen.index')->with('success', 'Data absen berhasil ditambahkan.');
    }

    // Menampilkan form edit absen
    public function edit($id)
    {
        $absen = Absen::findOrFail($id);
        return view('absen.edit', compact('absen'));
    }

    // Menyimpan perubahan data absen
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
            'gaji_per_jam' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $jamMasuk = strtotime($request->jam_masuk);
        $jamPulang = strtotime($request->jam_pulang);
        $totalJam = round(($jamPulang - $jamMasuk) / 3600, 2);
        $gaji = $totalJam * $request->gaji_per_jam;

        $absen = Absen::findOrFail($id);
        $absen->update([
            'nama' => $request->nama,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'total_jam' => $totalJam,
            'gaji_per_jam' => $request->gaji_per_jam,
            'gaji' => $gaji,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('absen.index')->with('success', 'Data absen berhasil diperbarui.');
    }

    // Menghapus data absen
    public function destroy($id)
    {
        $absen = Absen::findOrFail($id);
        $absen->delete();

        return redirect()->route('absen.index')->with('success', 'Data absen berhasil dihapus.');
    }
}
