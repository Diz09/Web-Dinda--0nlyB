<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

use App\Models\Karyawan;
use App\Models\Kloter;
use App\Models\Presensi;
use App\Models\TonIkan;
use App\Models\HistoryGajiKloter;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GajiKloterExport;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        // $klotersQuery = Kloter::with(['presensis', 'tonIkan'])->orderBy('id', 'desc');
        $klotersQuery = Kloter::with(['presensis', 'tonIkan'])->orderByDesc('id');
        $kloterSelesaiIds = HistoryGajiKloter::pluck('kloter_id')->toArray();

        if ($tahun) {
            $klotersQuery->whereHas('presensis', function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun);
            });
        }

        $kloters = $klotersQuery->get();

        // Filter manual untuk status
        if ($status === 'selesai') {
            $kloters = $kloters->filter(fn($kloter) =>
                HistoryGajiKloter::where('kloter_id', $kloter->id)->exists()
            );
        } elseif ($status === 'belum') {
            $kloters = $kloters->filter(fn($kloter) =>
                !HistoryGajiKloter::where('kloter_id', $kloter->id)->exists()
            );
        }

        $tahunList = Presensi::selectRaw('YEAR(tanggal) as tahun')->distinct()->pluck('tahun');

        return view('operator.gaji.index', 
            compact('kloters', 'tahun', 'tahunList', 'status', 'kloterSelesaiIds')
        );
    }

    public function detail($id)
    {
        $search = request('search');

        $kloter = Kloter::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);
        $tanggalUnik = $kloter->presensis->pluck('tanggal')->unique()->sort()->values();
        $dataKaryawan = $kloter->presensis->groupBy('karyawan_id');
        $banyakPekerja = $dataKaryawan->count();

        $jumlahTon = $kloter->tonIkan->jumlah_ton ?? 0;
        $hargaPerTon = $kloter->tonIkan->harga_ikan_per_ton ?? 1000000;
        $gajiPerJam = $banyakPekerja > 0 ? ($jumlahTon * $hargaPerTon) / $banyakPekerja : 0;

        $karyawanWithGaji = [];

        foreach ($dataKaryawan as $karyawanId => $presensis) {
            $karyawan = $presensis->first()->karyawan;
            $jamPerTanggal = [];
            $totalJam = 0;

            foreach ($tanggalUnik as $tanggal) {
                $presensi = $presensis->firstWhere('tanggal', $tanggal);
                $jamKerja = 0;

                if ($presensi && $presensi->jam_masuk && $presensi->jam_pulang) {
                    $jamMasuk = strtotime($presensi->jam_masuk);
                    $jamPulang = strtotime($presensi->jam_pulang);
                    $jamKerja = ($jamPulang - $jamMasuk) / 3600;
                }

                $jamPerTanggal[$tanggal] = $jamKerja;
                $totalJam += $jamKerja;
            }

            $totalGaji = $gajiPerJam * $totalJam;
            if ($karyawan->jenis_kelamin === 'P') {
                $totalGaji *= 0.6; // potongan 40%
            }

            $karyawanWithGaji[] = [
                'karyawan' => $karyawan,
                'jam_per_tanggal' => $jamPerTanggal,
                'total_jam' => $totalJam,
                'gaji_per_jam' => $gajiPerJam,
                'total_gaji' => $totalGaji,
            ];
        }

        if ($search) {
            $karyawanWithGaji = array_filter($karyawanWithGaji, function ($data) use ($search) {
                return stripos($data['karyawan']->nama, $search) !== false;
            });
        }

        return view('operator.gaji.detailGaji', compact(
            'kloter',
            'tanggalUnik',
            'dataKaryawan',
            'gajiPerJam',
            'jumlahTon',
            'hargaPerTon',
            'karyawanWithGaji'
        ))->with('selectedKloter', $kloter);
    }

    public function kloterSelesai($id)
    {
        $kloter = Kloter::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);

        if (HistoryGajiKloter::where('kloter_id', $kloter->id)->exists()) {
            return redirect()->route('gaji.index')->with('error', 'Kloter ini sudah ditandai selesai sebelumnya.');
        }

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
                $gaji *= 0.6; // potongan 40%
            }

            $totalGaji += $gaji;
        }

        HistoryGajiKloter::create([
            'kloter_id' => $kloter->id,
            'jml_karyawan' => $banyakPekerja,
            'total_gaji' => $totalGaji,
            'waktu' => now(),
        ]);

        return redirect()->route('gaji.kloter')->with('success', 'Kloter berhasil diselesaikan.');
    }

    public function export($id)
    {
        $kloter = \App\Models\Kloter::with('tonIkan')->findOrFail($id);

        $presensis = \App\Models\Presensi::with('karyawan')
            ->where('kloter_id', $id)
            ->get();

        $tanggalUnik = $presensis->pluck('tanggal')->unique()->sort()->values();

        $gajiPerJam = 0;
        $jumlahPekerja = $presensis->pluck('karyawan_id')->unique()->count();
        
        $jumlahTon = $kloter->tonIkan->jumlah_ton ?? 0;
        $hargaPerTon = $kloter->tonIkan->harga_ikan_per_ton ?? 1000000;

        if ($jumlahPekerja > 0) {
            $gajiPerJam = ($jumlahTon * $hargaPerTon) / $jumlahPekerja;
        }

        $data = [];

        foreach ($presensis->groupBy('karyawan_id') as $karyawanId => $presensiKaryawan) {
            $karyawan = $presensiKaryawan->first()->karyawan;
            $jamPerTanggal = [];

            foreach ($presensiKaryawan as $p) {
                $key = \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d');

                $jamKerja = 0;
                if ($p->jam_masuk && $p->jam_pulang) {
                    $jamMasuk = strtotime($p->jam_masuk);
                    $jamPulang = strtotime($p->jam_pulang);
                    $jamKerja = ($jamPulang - $jamMasuk) / 3600;
                }

                $jamPerTanggal[$key] = $jamKerja;
            }

            $totalJam = array_sum($jamPerTanggal);

            $gajiPerJamKaryawan = $karyawan->jenis_kelamin == 'Perempuan'
                ? $gajiPerJam * 0.6
                : $gajiPerJam;

            $totalGaji = $gajiPerJamKaryawan * $totalJam;

            $data[] = [
                'karyawan' => $karyawan,
                'jam_per_tanggal' => $jamPerTanggal,
                'total_jam' => $totalJam,
                'gaji_per_jam' => round($gajiPerJamKaryawan),
                'total_gaji' => round($totalGaji),
            ];

            // dd($data, $tanggalUnik);

        }

        return Excel::download(new GajiKloterExport($data, $tanggalUnik), 'detail_gaji_' . $kloter->nama_kloter . '.xlsx');
    }
}
