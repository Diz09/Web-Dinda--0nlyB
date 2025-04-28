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
    /*
        public function index()
        {
            $tanggalHariIni = date('Y-m-d');

            // Ambil jumlah ton hari ini (jika ada)
            $jumlahTonHariIni = DB::table('ton_ikan')
                ->whereDate('tanggal', $tanggalHariIni)
                ->value('jumlah_ton') ?? '';

            // Ambil data presensi karyawan hari ini
            $presensi = DB::table('presensi')
                ->whereDate('tanggal', $tanggalHariIni)
                ->get();

            // Ambil data karyawan
            $karyawan = DB::table('karyawan')->get();

            return view('gaji.index', compact('jumlahTonHariIni', 'presensi', 'karyawan'));
        }   
    */

    public function index()
    {
        $kuartals = Kuartal::with(['presensis', 'tonIkan'])->orderBy('id', 'desc')->get();

        return view('operator.gaji.index', compact('kuartals'));
    }


    public function detail($id)
    {
        $kuartal = Kuartal::with(['presensis.karyawan', 'tonIkan'])->findOrFail($id);
        
        // Ambil semua tanggal unik
        $tanggalUnik = $kuartal->presensis->pluck('tanggal')->unique()->sort()->values();

        // Group presensi berdasarkan karyawan
        $dataKaryawan = $kuartal->presensis->groupBy('karyawan_id');

        // Hitung banyak pekerja yang presensi
        $banyakPekerja = $dataKaryawan->count();

        // Ambil jumlah ton ikan dan harga per ton dari ton_ikans
        $jumlahTon = optional($kuartal->tonIkan)->jumlah_ton ?? 0;
        $hargaPerTon = optional($kuartal->tonIkan)->harga_ikan_per_ton ?? 1000000; // default kalau null

        // Hitung gaji per jam
        if ($banyakPekerja > 0) {
            $gajiPerJam = ($jumlahTon * $hargaPerTon) / $banyakPekerja;
        } else {
            $gajiPerJam = 0;
        }

        // Edit Simpan jumlah ton dan harga ikan per ton
        $jumlahTonHariIni = TonIkan::where('kuartal_id', $kuartal->id)
            ->value('jumlah_ton');

        $hargaIkanPerTon = TonIkan::where('kuartal_id', $kuartal->id)
            ->value('harga_ikan_per_ton');

        return view('operator.gaji.detailGaji', compact('kuartal', 'tanggalUnik', 'dataKaryawan', 'gajiPerJam', 'jumlahTonHariIni', 'hargaIkanPerTon'));
    }

}
