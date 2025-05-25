<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

use App\Models\Karyawan;
use App\Models\Kloter;
use App\Models\Presensi;
use App\Models\TonIkan;
use App\Models\HistoryGajiKloter;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        $klotersQuery = Kloter::with(['presensis', 'tonIkan'])->orderBy('id', 'desc');

        if ($tahun) {
            $klotersQuery->whereHas('presensis', function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun);
            });
        }

        $kloters = $klotersQuery->get();

        // Filter manual untuk status
        if ($status === 'selesai') {
            $kloters = $kloters->filter(function ($kloter) {
                return HistoryGajiKloter::where('kloter_id', $kloter->id)->exists();
            });
        } elseif ($status === 'belum') {
            $kloters = $kloters->filter(function ($kloter) {
                return !HistoryGajiKloter::where('kloter_id', $kloter->id)->exists();
            });
        }

        $tahunList = Presensi::selectRaw('YEAR(tanggal) as tahun')->distinct()->pluck('tahun');

        return view('operator.gaji.index', compact('kloters', 'tahun', 'tahunList', 'status'));
    }

    public function detail($id)
    {
        $kloter = Kloter::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);
        $selectedKloter = Kloter::with('tonIkan')->find($kloter->id);

        // Ambil semua tanggal unik
        $tanggalUnik = $kloter->presensis->pluck('tanggal')->unique()->sort()->values();

        // Group presensi berdasarkan karyawan
        $dataKaryawan = $kloter->presensis->groupBy('karyawan_id');

        // Hitung banyak pekerja yang presensi
        $banyakPekerja = $dataKaryawan->count();

        // Ambil jumlah ton dan harga per ton langsung dari relasi
        $jumlahTon = $kloter->tonIkan->jumlah_ton ?? 0;
        $hargaPerTon = $kloter->tonIkan->harga_ikan_per_ton ?? 1000000;

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
            'kloter', 
            'tanggalUnik', 
            'dataKaryawan', 
            'gajiPerJam', 
            'jumlahTon', 
            'hargaPerTon',
            'selectedKloter',
            'karyawanWithGaji'
        ));
    }

    public function kloterSelesai($id)
    {
        $kloter = Kloter::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);

        $alreadyDone = HistoryGajiKloter::where('kloter_id', $kloter->id)->exists();

        if ($alreadyDone) {
        return redirect()->route('gaji.index')->with('error', 'Kloter ini sudah ditandai selesai sebelumnya.');
        }

        $kloter = Kloter::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);

        $dataKaryawan = $kloter->presensis->groupBy('karyawan_id');
        $banyakPekerja = $dataKaryawan->count();

        $jumlahTon = $kloter->tonIkan->jumlah_ton ?? 0;
        $hargaPerTon = $kloter->tonIkan->harga_ikan_per_ton ?? 1000000;
        $gajiPerJam = $banyakPekerja > 0 ? ($jumlahTon * $hargaPerTon) / $banyakPekerja : 0;

        $totalGaji = 0;

        foreach ($dataKaryawan as $presensis) {
            $karyawan = $presensis->first()->karyawan;
            $totalJam = $presensis->sum(function ($presensi) {
                return (strtotime($presensi->jam_pulang) - strtotime($presensi->jam_masuk)) / 3600;
            });

            $gaji = $gajiPerJam * $totalJam;
            if ($karyawan->jenis_kelamin === 'P') {
                $gaji *= 0.8;
            }

            $totalGaji += $gaji;
        }

        HistoryGajiKloter::create([
            'kloter_id' => $kloter->id,
            'jml_karyawan' => $banyakPekerja,
            'total_gaji' => $totalGaji,
            'waktu' => now()
        ]);

        return redirect()->route('gaji.kloter')->with('success', 'Kloter berhasil diselesaikan.');
    }

}
