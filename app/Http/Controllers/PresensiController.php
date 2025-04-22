<?php
// app/Http/Controllers/PresensiController.php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\TonIkan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $tanggal = request()->get('tanggal', now()->toDateString());

        $karyawans = Karyawan::all();

        $presensis = Presensi::with('gaji')
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('karyawan_id'); // agar mudah dicocokkan
            
        // ambil jumlah ton hari ini dari DB
        $jumlahTonHariIni = TonIkan::whereDate('tanggal', $tanggal)->value('jumlah_ton');

        return view('operator.presensi.index', compact('karyawans', 'presensis', 'tanggal', 'jumlahTonHariIni'));
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

        // simpan presensi
        // $presensi = Presensi::create($request->only([
        //     'karyawan_id', 'tanggal', 'jam_masuk', 'jam_pulang'
        // ]));

        // hitung total jam kerja
        // $jamMasuk = Carbon::parse($request->jam_masuk);
        // $jamPulang = Carbon::parse($request->jam_pulang);
        // $totalJam = $jamMasuk->diffInMinutes($jamPulang) / 60;

        // // hitung gaji
        // $jumlahPekerja = Presensi::where('tanggal', $request->tanggal)->distinct('karyawan_id')->count();
        // $tonIkan = TonIkan::where('tanggal', $request->tanggal)->first();

        // if (!$tonIkan || $jumlahPekerja == 0) {
        //     return redirect()->back()->withErrors('Ton ikan atau pekerja tidak valid.');
        // }

        // $gajiPerJam = ($tonIkan->jumlah_ton * 1000) / $jumlahPekerja;
        // $karyawan = Karyawan::find($request->karyawan_id);

        // if ($karyawan->jenis_kelamin === 'P') {
        //     $gajiPerJam *= 0.6;
        // }

        // $totalGaji = round($gajiPerJam * $totalJam);

        // // simpan gaji
        // Gaji::create([
        //     'presensi_id' => $presensi->id,
        //     'total_jam' => $totalJam,
        //     'gaji_pokok' => $totalGaji,
        //     // 'gaji_lembur' => 0,
        //     'total_gaji' => $totalGaji,
        // ]);

        return redirect()->route('presensi.index')->with('success', 'Presensi dan gaji berhasil disimpan.');
    }

    public function inputMasuk($id)
    {
        $presensi = Presensi::firstOrCreate(
            ['karyawan_id' => $id, 'tanggal' => now()->toDateString()],
            ['jam_masuk' => Carbon::now()->format('H:i:s')]
        );

        if (!$presensi->wasRecentlyCreated && !$presensi->jam_masuk) {
            $presensi->jam_masuk = Carbon::now()->format('H:i:s');
            $presensi->save();
        }

        return redirect()->back()->with('success', 'Jam masuk berhasil disimpan.');
    }

    public function inputPulang($id)
    {
        $presensi = Presensi::with('karyawan')
            ->where('karyawan_id', $id)
            ->where('tanggal', now()->toDateString())
            ->first();

        if ($presensi && !$presensi->jam_pulang) {
            $presensi->jam_pulang = Carbon::now()->format('H:i:s');
            $presensi->save();

            // 🔥 Hitung gaji langsung
            $jamMasuk = Carbon::parse($presensi->jam_masuk);
            $jamPulang = Carbon::parse($presensi->jam_pulang);
            $totalJam = round($jamMasuk->diffInMinutes($jamPulang) / 60, 2);

            $tanggal = $presensi->tanggal;
            $jumlahPekerja = Presensi::whereDate('tanggal', $tanggal)->distinct('karyawan_id')->count();
            $tonIkan = TonIkan::whereDate('tanggal', $tanggal)->value('jumlah_ton') ?? 0;

            if ($jumlahPekerja > 0 && $tonIkan > 0) {
                $gajiPerJam = ($tonIkan * 1000) / $jumlahPekerja;

                // Pengurangan 40% untuk pekerja perempuan
                if ($presensi->karyawan->jenis_kelamin === 'P') {
                    $gajiPerJam *= 0.6;
                }

                $gajiPerJam = round($gajiPerJam, 2);
                $totalGaji = round($gajiPerJam * $totalJam);

                Gaji::updateOrCreate(
                    ['presensi_id' => $presensi->id],
                    [
                        'total_jam' => $totalJam,
                        'gaji_pokok' => $totalGaji,
                        'total_gaji' => $totalGaji
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Jam pulang dan gaji berhasil disimpan.');
    }

}
