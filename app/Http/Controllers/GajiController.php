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

        // Edit Simpan jumlah ton dan harga ikan per ton
        // $jumlahTonHariIni = TonIkan::where('kuartal_id', $kuartal->id)
        //     ->value('jumlah_ton');

        // $hargaIkanPerTon = TonIkan::where('kuartal_id', $kuartal->id)
        //     ->value('harga_ikan_per_ton');

        return view('operator.gaji.detailGaji', compact(
            'kuartal', 
            'tanggalUnik', 
            'dataKaryawan', 
            'gajiPerJam', 
            'jumlahTon', 
            'hargaPerTon',
            'selectedKuartal',

            // 'jumlahTonHariIni',
            // 'hargaIkanPerTon'
        ));
    }

}
