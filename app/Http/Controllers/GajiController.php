<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

use App\Models\Karyawan;
use App\Models\Kuartal;
use App\Models\Presensi;
use App\Models\TonIkan;

class GajiController extends Controller
{
    public function index()
    {
        $kuartals = Kuartal::with(['presensis', 'tonIkan'])->orderBy('id', 'desc')->get();

        return view('operator.gaji.index', compact('kuartals'));
    }

    public function detail($id)
    {
        $kuartal = Kuartal::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);
        $selectedKuartal = Kuartal::with('tonIkan')->find($kuartal->id);

        // Ambil semua tanggal unik
        $tanggalUnik = $kuartal->presensis->pluck('tanggal')->unique()->sort()->values();

        // Group presensi berdasarkan karyawan
        $dataKaryawan = $kuartal->presensis->groupBy('karyawan_id');

        // Hitung banyak pekerja yang presensi
        $banyakPekerja = $dataKaryawan->count();

        // Ambil jumlah ton dan harga per ton langsung dari relasi
        $jumlahTon = $kuartal->tonIkan->jumlah_ton ?? 0;
        $hargaPerTon = $kuartal->tonIkan->harga_ikan_per_ton ?? 1000000;

        // Hitung gaji per jam
        $gajiPerJam = $banyakPekerja > 0 ? ($jumlahTon * $hargaPerTon) / $banyakPekerja : 0;

        // Siapkan array untuk data karyawan yang sudah diproses lengkap
        $karyawanWithGaji = [];

        foreach ($dataKaryawan as $karyawanId => $presensis) {
            $karyawan = $presensis->first()->karyawan;

            $jamPerTanggal = [];
            $totalJam = 0;

            // Hitung jam kerja per tanggal
            foreach ($tanggalUnik as $tanggal) {
                $presensiTanggal = $presensis->where('tanggal', $tanggal)->first();
                if ($presensiTanggal) {
                    $jamMasuk = strtotime($presensiTanggal->jam_masuk);
                    $jamPulang = strtotime($presensiTanggal->jam_pulang);
                    $jamKerja = ($jamPulang - $jamMasuk) / 3600;
                } else {
                    $jamKerja = 0;
                }
                $jamPerTanggal[$tanggal] = $jamKerja;
                $totalJam += $jamKerja;
            }

            // Hitung total gaji dengan potongan 20% jika jenis kelamin P
            $totalGaji = $gajiPerJam * $totalJam;
            if ($karyawan->jenis_kelamin === 'P') {
                $totalGaji *= 0.8; // diskon 20%
            }

            // Tambahkan data lengkap karyawan ke array
            $karyawanWithGaji[] = [
                'karyawan' => $karyawan,
                'jam_per_tanggal' => $jamPerTanggal,
                'total_jam' => $totalJam,
                'gaji_per_jam' => $gajiPerJam,
                'total_gaji' => $totalGaji,
            ];
        }

        return view('operator.gaji.detailGaji', compact(
            'kuartal', 
            'tanggalUnik', 
            'dataKaryawan', 
            'gajiPerJam', 
            'jumlahTon', 
            'hargaPerTon',
            'selectedKuartal',
            'karyawanWithGaji'

            // 'jumlahTonHariIni',
            // 'hargaIkanPerTon'
        ));
    }

}
