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

    private function getPendapatan($query)
    {
        return (float) (clone $query)->whereNotNull('pemasukan_id')->sum('jumlahRp');
    }

    private function getPengeluaran($query)
    {
        return (float) (clone $query)->where(function ($q) {
            $q->whereNotNull('pengeluaran_id')
            ->orWhereNotNull('history_gaji_kloter_id');
        })->sum('jumlahRp');
    }
    
    public function pimpinan()
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfYear();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->endOfMonth();

        $current = $startDate->copy()->startOfMonth();
        $end = $endDate->copy()->endOfMonth();

        $labels = [];
        $pendapatanFinal = [];
        $pengeluaranFinal = [];

        while ($current <= $end) {
            $bulan = $current->month;
            $tahun = $current->year;

            $labels[] = $current->format('F Y');

            $totalPendapatan = Transaksi::whereMonth('waktu_transaksi', $bulan)
                ->whereYear('waktu_transaksi', $tahun)
                ->whereBetween('waktu_transaksi', [$startDate, $endDate])
                ->whereNotNull('pemasukan_id')
                ->sum(DB::raw('qtyHistori * jumlahRp'));

            $totalPengeluaran = Transaksi::whereMonth('waktu_transaksi', $bulan)
                ->whereYear('waktu_transaksi', $tahun)
                ->whereBetween('waktu_transaksi', [$startDate, $endDate])
                ->whereNotNull('pengeluaran_id')
                ->sum(DB::raw('qtyHistori * jumlahRp'));

            $pendapatanFinal[] = $totalPendapatan;
            $pengeluaranFinal[] = $totalPengeluaran;

            $current->addMonth();
        }

        [$pendapatanCompressed, $pengeluaranCompressed, $labelHints] = $this->compressExtremeDiff($pendapatanFinal, $pengeluaranFinal);

        dd(Transaksi::whereMonth('waktu_transaksi', $bulan)
            ->whereYear('waktu_transaksi', $tahun)
            ->whereBetween('waktu_transaksi', [$startDate, $endDate])
            ->whereNotNull('pemasukan_id')
            ->get(['qtyHistori', 'jumlahRp']));

        return view('dashboard.pimpinan', [
            'labels' => $labels,
            'pendapatanBulanan' => $pendapatanCompressed,
            'pengeluaranBulanan' => $pengeluaranCompressed,
            'originalLabels' => $labels,
            'extraLabelHints' => $labelHints,
        ]);
    }

    private function compressExtremeDiff($data1, $data2)
    {
        $compressed1 = [];
        $compressed2 = [];
        $displayLabels = [];

        for ($i = 0; $i < count($data1); $i++) {
            $val1 = $data1[$i];
            $val2 = $data2[$i];
            $selisih = abs($val1 - $val2);
            $maxVal = max($val1, $val2);

            $threshold = pow(10, floor(log10($maxVal))) * 0.3;

            if ($selisih > $threshold) {
                // Tandai ekstrem, tampilkan "..." dan skip nilai
                $compressed1[] = null;
                $compressed2[] = null;
                $displayLabels[] = '...';
            } else {
                $compressed1[] = $val1;
                $compressed2[] = $val2;
                $displayLabels[] = null;
            }
        }

        return [$compressed1, $compressed2, $displayLabels];
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
