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
use App\Models\HistoryGajiKloter;

class DashboardController extends Controller
{
    // Menampilkan dashboard pimpinan.
    public function pimpinan(Request $request)
    {
        $currentYear = now()->year;
        $start = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date')) 
            : Carbon::create($currentYear, 1, 1)->startOfDay();
        $end = $request->get('end_date') 
            ? Carbon::parse($request->get('end_date')) 
            : Carbon::create($currentYear, 12, 31)->endOfDay();

        $kloters = HistoryGajiKloter::whereNotNull('tanggal_awal')
            ->whereNotNull('tanggal_akhir')
            ->orderBy('id', 'desc')
            ->get();

        $query = Transaksi::whereBetween('waktu_transaksi', [$start, $end]);

        $dates = CarbonPeriod::create($start, $end);

        $labels = [];
        $pendapatanBulanan = [];
        $pengeluaranBulanan = [];

        foreach ($dates as $date) {
            $labels[] = $date->format('d M');

            $pendapatan = Transaksi::whereDate('waktu_transaksi', $date)
                ->whereNotNull('pemasukan_id')
                ->sum('jumlahRp');

            $pengeluaran = Transaksi::whereDate('waktu_transaksi', $date)
                ->where(function ($q) {
                    $q->whereNotNull('pengeluaran_id')
                    ->orWhereNotNull('history_gaji_kloter_id');
                })
                ->sum('jumlahRp');

            $pendapatanBulanan[] = $pendapatan;
            $pengeluaranBulanan[] = $pengeluaran;
        }

        $bulanAktif = $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y');

        $totalPendapatan = Transaksi::whereBetween('waktu_transaksi', [$start, $end])
            ->whereNotNull('pemasukan_id')
            ->sum('jumlahRp');

        $totalPengeluaran = Transaksi::whereBetween('waktu_transaksi', [$start, $end])
            ->where(function ($q) {
                $q->whereNotNull('pengeluaran_id')
                ->orWhereNotNull('history_gaji_kloter_id');
            })
            ->sum('jumlahRp');

        $keuangan = [
            'pendapatan' => $totalPendapatan,
            'pengeluaran' => $totalPengeluaran
        ];

        return view('dashboard.pimpinan', compact(
            'labels', 
            'pendapatanBulanan', 
            'pengeluaranBulanan', 
            'keuangan', 
            'bulanAktif',
            'kloters'
        ));
    }


    // Menampilkan dashboard operator
    public function operator()
    {
        // Ambil 5 data barang terbaru
        $barangTerbaru = Barang::with(['produk', 'pendukung'])
            ->latest()
            ->take(5)
            ->get();

        // Ambil 5 transaksi terbaru
        $transaksiTerbaru = Transaksi::with(['barang', 'pemasukan', 'pengeluaran',  'historyGajiKloter'])
        ->orderBy('waktu_transaksi', 'desc')
        ->take(5)
        ->get()
        ->map(function ($trx) {
            $kategori = $trx->pengeluaran_id === null ? 'Masuk' : 'Keluar';
            $harga = ($kategori === 'Masuk' ? '+ ' : '- ') . 'Rp ' . number_format($trx->jumlahRp, 0, ',', '.');

            // Tentukan nama transaksi
            $namaTransaksi = $trx->historyGajiKloter
                ? 'Pembayaran Gaji Kloter #' . $trx->historyGajiKloter->id
                : ($trx->barang->nama_barang ?? '-');

            return [
                'waktu' => $trx->waktu_transaksi,
                'nama_barang' => $namaTransaksi,
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
