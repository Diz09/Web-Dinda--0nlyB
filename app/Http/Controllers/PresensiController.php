<?php
// app/Http/Controllers/PresensiController.php

namespace App\Http\Controllers;

use App\Models\Kuartal;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\TonIkan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index(Request $request, $kuartal_id = null)
    {
        $tanggal = now()->toDateString();
        $kuartals = Kuartal::orderBy('id', 'desc')->get();

        // Tombol buat kuartal baru
        if ($request->get('buat_kuartal')) {
            $selectedKuartal = Kuartal::create([
                'nama_kuartal' => 'Kuartal-' . (Kuartal::count() + 1)
            ]);
            return redirect()->route('presensi.index', ['kuartal_id' => $selectedKuartal->id]);
        }

        // Tombol pilih kuartal
        $selectedKuartal = $kuartal_id
            ? Kuartal::with('tonIkan')->find($kuartal_id)
            : Kuartal::latest()->first();

        if (!$selectedKuartal) {
            $selectedKuartal = Kuartal::create([
                'nama_kuartal' => 'Kuartal-' . (Kuartal::count() + 1)
            ]);
        }

        $karyawans = Karyawan::all();

        $presensis = Presensi::where('kuartal_id', $selectedKuartal->id)
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('karyawan_id');

        $jumlahTonHariIni = TonIkan::where('kuartal_id', $selectedKuartal->id)
            ->value('jumlah_ton');

        $hargaIkanPerTon = TonIkan::where('kuartal_id', $selectedKuartal->id)
            ->value('harga_ikan_per_ton') ?? 1000000;

        return view('operator.presensi.index', compact('karyawans', 'presensis', 'tanggal', 'kuartals', 'selectedKuartal', 'jumlahTonHariIni', 'hargaIkanPerTon'));
    }

    public function inputMasuk(Request $request, $id)
    {
        $kuartalId = $request->input('kuartal_id');
        $tanggal = now()->toDateString();

        $presensi = Presensi::where('karyawan_id', $id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if (!$presensi) {
            $presensi = Presensi::create([
                'karyawan_id' => $id,
                'kuartal_id' => $kuartalId,
                'tanggal' => $tanggal,
                'jam_masuk' => Carbon::now()->format('H:i:s')
            ]);
        } elseif (!$presensi->jam_masuk) {
            $presensi->jam_masuk = Carbon::now()->format('H:i:s');
            $presensi->kuartal_id = $kuartalId;
            $presensi->save();
        }

        return redirect()->back()->with('success', 'Jam masuk berhasil disimpan.');
    }

    public function inputPulang(Request $request, $id)
    {
        $presensi = Presensi::where('karyawan_id', $id)
            ->where('tanggal', now()->toDateString())
            ->first();

        if ($presensi && !$presensi->jam_pulang) {
            $presensi->jam_pulang = Carbon::now()->format('H:i:s');
            $presensi->save();
        }

        return redirect()->back()->with('success', 'Jam pulang berhasil disimpan.');
    }

    public function simpanTonIkan(Request $request)
    {
        $request->validate([
            'kuartal_id' => 'required|exists:kuartals,id',
            // 'tanggal' => 'required|date',
            'jumlah_ton' => 'required|numeric'
        ]);

        TonIkan::updateOrCreate(
            ['kuartal_id' => $request->kuartal_id/*, 'tanggal' => $request->tanggal*/],
            ['jumlah_ton' => $request->jumlah_ton]
        );

        return redirect()->route('presensi.index', ['kuartal_id' => $request->kuartal_id])->with('success', 'Data ton ikan berhasil disimpan.');
    }
}
