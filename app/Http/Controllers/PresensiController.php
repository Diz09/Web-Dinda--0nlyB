<?php
// app/Http/Controllers/PresensiController.php

namespace App\Http\Controllers;

use App\Models\Kloter;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\TonIkan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index(Request $request, $kloter_id = null)
    {
        $tanggal = now()->toDateString();
        $kloters = Kloter::orderBy('id', 'desc')->get();

        // Tombol buat kloter baru
        if ($request->get('buat_kloter')) {
            $selectedKloter = Kloter::create([
                'nama_kloter' => 'Kloter-' . (Kloter::count() + 1)
            ]);
            return redirect()->route('presensi.index', ['kloter_id' => $selectedKloter->id]);
        }

        // Tombol pilih kloter
        $selectedKloter = $kloter_id
            ? Kloter::with('tonIkan')->find($kloter_id)
            : Kloter::latest()->first();

        if (!$selectedKloter) {
            $selectedKloter = Kloter::create([
                'nama_kloter' => 'Kloter-' . (Kloter::count() + 1)
            ]);
        }

        $karyawans = Karyawan::all();

        $presensis = Presensi::where('kloter_id', $selectedKloter->id)
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('karyawan_id');

        $jumlahTonHariIni = TonIkan::where('kloter_id', $selectedKloter->id)
            ->value('jumlah_ton') ?? 0;

        $hargaIkanPerTon = TonIkan::where('kloter_id', $selectedKloter->id)
            ->value('harga_ikan_per_ton') ?? 1000000;

        return view('operator.presensi.index', compact('karyawans', 'presensis', 'tanggal', 'kloters', 'selectedKloter', 'jumlahTonHariIni', 'hargaIkanPerTon'));
    }

    public function inputMasuk(Request $request, $id)
    {
        $kloterId = $request->input('kloter_id');
        $tanggal = now()->toDateString();
        $jamInput = $request->input('jam') ?? Carbon::now()->format('H:i:s'); // <- ambil jam dari request jika ada

        // Validasi: jam tidak boleh lebih dari waktu sekarang
        $sekarang = now()->format('H:i:s');
        if ($jamInput > $sekarang) {
            return redirect()->back()->with('error', 'Jam masuk tidak boleh lebih dari waktu saat ini.');
        }

        $presensi = Presensi::where('karyawan_id', $id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if (!$presensi) {
            $presensi = Presensi::create([
                'karyawan_id' => $id,
                'kloter_id' => $kloterId,
                'tanggal' => $tanggal,
                'jam_masuk' => $jamInput
            ]);
        } elseif (!$presensi->jam_masuk) {
            $presensi->jam_masuk = $jamInput;
            $presensi->kloter_id = $kloterId;
            $presensi->save();
        }

        return redirect()->back()->with('success', 'Jam masuk berhasil disimpan.');
    }

    public function inputPulang(Request $request, $id)
    {
        $kloterId = $request->input('kloter_id');
        $tanggal = now()->toDateString();
        $jamInput = $request->input('jam') ?? Carbon::now()->format('H:i:s');

        // Validasi: jam tidak boleh lebih dari waktu sekarang
        $sekarang = now()->format('H:i:s');
        if ($jamInput > $sekarang) {
            return redirect()->back()->with('error', 'Jam pulang tidak boleh lebih dari waktu saat ini.');
        }

        $presensi = Presensi::where('karyawan_id', $id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($presensi && !$presensi->jam_pulang) {
            $presensi->jam_pulang = $jamInput;
            $presensi->kloter_id = $kloterId;
            $presensi->save();
        }

        return redirect()->back()->with('success', 'Jam pulang berhasil disimpan.');
    }

    public function simpanTonIkan(Request $request)
    {
        $request->validate([
            'kloter_id' => 'required|exists:kloters,id',
            'jumlah_ton' => 'required|numeric',
            'harga_ikan_per_ton' => 'required|numeric',
        ]);

        TonIkan::updateOrCreate(
            ['kloter_id' => $request->kloter_id/*, 'tanggal' => $request->tanggal*/],
            [
                'jumlah_ton' => $request->jumlah_ton ?? 0,
                'harga_ikan_per_ton' => $request->harga_ikan_per_ton
            ],
        );

        return redirect()->back()->with('success', 'Data ton ikan berhasil disimpan.');
    }
}
