<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Kuartal;
use App\Models\TonIkan;
use App\Models\Transaksi;

use App\Exports\LaporanKaryawanExport;
use App\Exports\LaporanTransaksiExport;

class LaporanController extends Controller
{
    public function karyawan(Request $request)
    {
        $filter = $request->input('filter', 'minggu_ini');
        $nama = $request->input('nama');

        switch ($filter) {
            case 'kuartal_terbaru':
                // Ambil kuartal yang memiliki presensi dengan tanggal paling akhir
                $presensiTerbaru = Presensi::latest('tanggal')->first();

                if ($presensiTerbaru && $presensiTerbaru->kuartal) {
                    $kuartalTerbaru = $presensiTerbaru->kuartal;

                    // Gunakan accessor dari model Kuartal
                    $startDate = Carbon::parse($kuartalTerbaru->tanggal_mulai);
                    $endDate = Carbon::parse($kuartalTerbaru->tanggal_akhir);
                } else {
                    $startDate = $endDate = null;
                }
                break;
            case 'hari_ini':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'bulan_ini':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'semua':
                $startDate = null;
                $endDate = null;
                break;
            case 'minggu_ini':
            default:
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
        }

        // Ambil semua kuartal yang memiliki ton ikan
        $kuartals = Kuartal::with(['tonIkan', 'presensis'])
            ->get()
            ->filter(fn($kuartal) => $kuartal->tonIkan);

        // Hitung gaji per jam tiap kuartal
        // $gajiPerJamKuartal = [];

        $gajiPerHariKuartal = [];

        foreach ($kuartals as $kuartal) {
            $tonIkan = $kuartal->tonIkan;
            $jumlahTon = $tonIkan->jumlah_ton ?? 0;
            $hargaPerTon = $tonIkan->harga_ikan_per_ton ?? 0;

            $karyawanUnik = Presensi::where('kuartal_id', $kuartal->id)
                ->distinct('karyawan_id')
                ->pluck('karyawan_id');

            $jumlahPekerjaKuartal = $karyawanUnik->count();

            $gajiPerHariKuartal[$kuartal->id] = $jumlahPekerjaKuartal > 0
                ? ($jumlahTon * $hargaPerTon) / $jumlahPekerjaKuartal
                : 0;
        }


        $karyawans = Karyawan::with('presensis.kuartal')
            ->when($nama, fn($query) => $query->where('nama', 'like', '%' . $nama . '%'))
            ->get();

        $data = [];

        foreach ($karyawans as $karyawan) {
            $totalJamKerja = 0;
            $totalGaji = 0;

            // Ambil semua presensi karyawan, filter sesuai tanggal (misal filter minggu ini/hari ini)
            $presensis = $karyawan->presensis()
                ->when($startDate && $endDate, fn($query) => $query->whereBetween('tanggal', [$startDate, $endDate]))
                ->with('kuartal')
                ->get();

            // Kelompokkan presensi per kuartal
            $presensiPerKuartal = $presensis->groupBy('kuartal_id');

            $gajiPerKuartal = []; // Hitung gaji per kuartal

            foreach ($presensiPerKuartal as $kuartalId => $presensiKuartal) {
                // Hitung total jam kerja karyawan di kuartal ini
                $totalJamKerjaKuartal = 0;
                foreach ($presensiKuartal as $presensi) {
                    $jamMasuk = Carbon::parse($presensi->jam_masuk);
                    $jamPulang = Carbon::parse($presensi->jam_pulang);
                    $jamKerja = $jamPulang->diffInSeconds($jamMasuk) / 3600;
                    $totalJamKerjaKuartal += $jamKerja;
                }

                $totalJamKerja += $totalJamKerjaKuartal;

                // Gaji per hari kuartal (dari hasil sebelumnya)
                $gajiPerHari = $gajiPerHariKuartal[$kuartalId] ?? 0;  // Variabel ini perlu kamu hitung sebelum loop karyawan

                // Hitung gaji per jam untuk karyawan di kuartal ini
                $gajiPerJam = $totalJamKerjaKuartal > 0 ? ($gajiPerHari / $totalJamKerjaKuartal) : 0;

                // Hitung gaji kuartal ini
                $gajiKuartalIni = $gajiPerJam * $totalJamKerjaKuartal;

                // Simpan detail per kuartal
                $gajiPerKuartal[] = [
                    'kuartal_id' => $kuartalId,
                    'total_jam' => round($totalJamKerjaKuartal, 2),
                    'gaji' => round($gajiKuartalIni, 0),
                ];

                // Tambah total gaji karyawan
                $totalGaji += $gajiKuartalIni;
            }

            // Potong 20% jika karyawan perempuan
            if ($karyawan->jenis_kelamin === 'P') {
                $totalGaji *= 0.8;
            }

            $data[] = [
                'karyawan' => $karyawan,
                'total_jam_kerja' => round($totalJamKerja, 2),
                'gaji_per_kuartal' => $gajiPerKuartal,
                'total_gaji' => round($totalGaji, 0),
            ];
        }

        if ($request->ajax()) {
            return view('pimpinan.laporan_karyawan._table', compact('data'));
        }

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new LaporanKaryawanExport($data), 'laporan_karyawan.xlsx');
        }

        return view('pimpinan.laporan_karyawan.index', compact('data', 'filter', 'nama'));
    }

    public function transaksi(Request $request)
    {
        $query = Transaksi::with([
            'barang.produk', 
            'barang.pendukung', 
            'supplier', 
            'pemasukan', 
            'pengeluaran'
        ]);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($q1) use ($search) {
                    $q1->where('nama_barang', 'like', '%' . $search . '%');
                })->orWhereHas('supplier', function ($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%');
                })->orWhereHas('barang.produk', function ($q3) use ($search) {
                    $q3->where('kode', 'like', '%' . $search . '%');
                })->orWhereHas('barang.pendukung', function ($q4) use ($search) {
                    $q4->where('kode', 'like', '%' . $search . '%');
                })->orWhereHas('pemasukan', function ($q5) use ($search) {
                    $q5->where('kode', 'like', '%' . $search . '%');
                })->orWhereHas('pengeluaran', function ($q6) use ($search) {
                    $q6->where('kode', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter tanggal jika ada input
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('waktu_transaksi', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_akhir . ' 23:59:59'
            ]);
        }

        $transaksis = $query->orderBy('waktu_transaksi')->get();

        $totalSebelumnya = 0;

        $data = $transaksis->map(function ($trx) use (&$totalSebelumnya) {
            $kodeBarang = $trx->barang->produk->kode ?? $trx->barang->pendukung->kode ?? '-';
            $masuk = $trx->pemasukan_id ? $trx->jumlahRp : 0;
            $keluar = $trx->pengeluaran_id ? $trx->jumlahRp : 0;

            $totalSekarang = $totalSebelumnya + $masuk - $keluar;
            $totalSebelumnya = $totalSekarang;

            return [
                'waktu' => $trx->waktu_transaksi,
                'kode_transaksi' => $trx->pemasukan->kode ?? $trx->pengeluaran->kode ?? '-',
                'kode_barang' => $kodeBarang,
                'supplier' => $trx->supplier->nama ?? '-',
                'nama_barang' => $trx->barang->nama_barang ?? '-',
                'qty' => $trx->qtyHistori ?? 0,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'total' => $totalSekarang,
            ];
        })->reverse()->values();

        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new LaporanTransaksiExport(
                $request->tanggal_mulai,
                $request->tanggal_akhir,
                $request->q
            ), 'laporan_transaksi.xlsx');
        }
        
        if ($request->ajax()) {
            return view('pimpinan.laporan_transaksi._table', compact('data'));
        }

        return view('pimpinan.laporan_transaksi.index', compact('data'));
    }

}
