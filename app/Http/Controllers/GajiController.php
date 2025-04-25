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


    public function rekap(Request $request)
    {
        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');

        if (!$mulai || !$akhir) {
            return view('operator.gaji.index', ['karyawans' => [], 'tanggalRange' => [], 'mulai' => $mulai, 'akhir' => $akhir]);
        }

        $tanggalRange = CarbonPeriod::create($mulai, $akhir)->toArray(); // daftar tanggal

        // $karyawans = Karyawan::with(['presensis.gaji' => function ($q) use ($mulai, $akhir) {
        //     $q->whereBetween('tanggal', [$mulai, $akhir]);
        // }, 'presensis' => function ($q) use ($mulai, $akhir) {
        //     $q->whereBetween('tanggal', [$mulai, $akhir]);
        // }])->get();

        $karyawans = Karyawan::with(['presensis' => function ($q) use ($mulai, $akhir) {
            $q->whereBetween('tanggal', [$mulai, $akhir]);
        }, 'presensis.gaji'])->get();
        

        return view('operator.gaji.index', compact('karyawans', 'tanggalRange', 'mulai', 'akhir'));
    }

}
