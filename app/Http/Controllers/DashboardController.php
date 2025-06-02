<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

use App\Models\Barang;
use App\Models\BarangPendukung;
use App\Models\BarangProduk;
use App\Models\Transaksi;
use App\Models\Pengeluaran;

class DashboardController extends Controller
{
    // Menampilkan dashboard pimpinan.
    public function pimpinan(Request $request)
    {
        $filter = $request->get('filter', 'tahun');
        $query = Transaksi::query();

        if ($filter === 'bulan') {
            $year = $request->get('year', now()->year);
            $month = $request->get('month', now()->month);
            $bulanAktif = \Carbon\Carbon::create($year, $month)->translatedFormat('F Y');
            $query->whereYear('waktu_transaksi', $year)->whereMonth('waktu_transaksi', $month);

            // Bulan: labels = tanggal 1 - akhir
            $start = Carbon::create($year, $month, 1);
            $end = $start->copy()->endOfMonth();
            $dates = CarbonPeriod::create($start, $end);

            $labels = [];
            $pendapatanBulanan = [];
            $pengeluaranBulanan = [];

            foreach ($dates as $date) {
                $labels[] = $date->format('d');
                $pendapatan = (clone $query)
                    ->whereDate('waktu_transaksi', $date)
                    ->whereNotNull('pemasukan_id')
                    ->sum('jumlahRp');

                $pengeluaran = (clone $query)
                    ->whereDate('waktu_transaksi', $date)
                    ->whereNotNull('pengeluaran_id')
                    ->sum('jumlahRp');

                $pendapatanBulanan[] = $pendapatan;
                $pengeluaranBulanan[] = $pengeluaran;
            }
        } else { // filter tahun (default)
            $year = $request->get('year', now()->year);
            $bulanAktif = "Tahun $year";
            $query->whereYear('waktu_transaksi', $year);

            $labels = [];
            $pendapatanBulanan = [];
            $pengeluaranBulanan = [];

            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M');

                $pendapatan = (clone $query)
                    ->whereMonth('waktu_transaksi', $i)
                    ->whereNotNull('pemasukan_id')
                    ->sum('jumlahRp');

                $pengeluaran = (clone $query)
                    ->whereMonth('waktu_transaksi', $i)
                    ->whereNotNull('pengeluaran_id')
                    ->sum('jumlahRp');

                $pendapatanBulanan[] = $pendapatan;
                $pengeluaranBulanan[] = $pengeluaran;
            }
        }

        $totalPendapatan = Transaksi::whereNotNull('pemasukan_id')->sum('jumlahRp');
        $totalPengeluaran = Transaksi::whereNotNull('pengeluaran_id')->sum('jumlahRp');

        $keuangan = [
            'pendapatan' => $totalPendapatan,
            'pengeluaran' => $totalPengeluaran
        ];

        return view('dashboard.pimpinan', compact('labels', 'pendapatanBulanan', 'pengeluaranBulanan', 'keuangan', 'bulanAktif'));
    }

    // Menampilkan dashboard operator
    public function operator()
    {
        // Ambil 5 data barang terbaru
        $barangTerbaru = Barang::with(['produk', 'pendukung'])->latest()->take(5)->get();

        // Ambil 5 transaksi terbaru
        $transaksiTerbaru = Transaksi::with(['barang', 'pemasukan', 'pengeluaran'])
        ->orderBy('waktu_transaksi', 'desc')
        ->take(5)
        ->get()
        ->map(function ($trx) {
            $kategori = $trx->pengeluaran_id === null ? 'Masuk' : 'Keluar';
            $harga = ($kategori === 'Masuk' ? '+ ' : '- ') . 'Rp ' . number_format($trx->jumlahRp, 0, ',', '.');

            return [
                'waktu' => $trx->waktu_transaksi,
                'nama_barang' => $trx->barang->nama_barang ?? '-',
                'kategori' => $kategori,
                'harga' => $harga,
            ];
        });

        // Jumlah total barang
        $jBarang = Barang::sum('qty');
        $jumlahBarang = number_format($jBarang, 0, ',', '.') . ' kg';

        return view('dashboard.operator', compact('barangTerbaru', 'transaksiTerbaru', 'jumlahBarang'));
    }

    public function tambahUangMakanHarian()
    {
        try {
            DB::beginTransaction();

            // Ambil barang uang makan
            $barang = Barang::where('nama_barang', 'like', '%uang makan%')->first();

            if (!$barang) {
                return back()->with('error', 'Barang uang makan tidak ditemukan.');
            }

            // Cek apakah stok mencukupi
            if ($barang->qty < 1) {
                return back()->with('error', 'Stok uang makan tidak mencukupi.');
            }

            // Kurangi stok
            $barang->qty -= 1;
            $barang->save();

            $pengeluaran = Pengeluaran::create([
                'kode' => 'KLR' . str_pad(Pengeluaran::max('id') + 1, 3, '0', STR_PAD_LEFT),
            ]);

            Transaksi::create([
                'barang_id' => $barang->id,
                'pengeluaran_id' => $pengeluaran->id,
                'qtyHistori' => 1,
                'satuan' => 'paket', // satuan statis
                'jumlahRp' => $barang->harga,
                'waktu_transaksi' => now(),
                'supplier_id' => null,
            ]);

            DB::commit();

            return back()->with('success', 'Transaksi uang makan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan uang makan: ' . $e->getMessage());
        }
    }

}
