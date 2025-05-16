<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\BarangProduk;
use App\Models\BarangPendukung;
use App\Models\Supplier;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with([
            'barang.produk', 
            'barang.pendukung', 
            'supplier', 
            'pemasukan', 
            'pengeluaran'
        ]);

        // filter tanggal jika diisi
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('waktu_transaksi', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_akhir . ' 23:59:59'
            ]);
        // lanjutkan kebawah nya jika ingin menganti default dengan bulan ini
        // } else {
        //     // Default: bulan ini
        //     $query->whereBetween('waktu_transaksi', [
        //         now()->startOfMonth(),
        //         now()->endOfMonth()
        //     ]);
        }

        $transaksis = $query->orderBy('waktu_transaksi')->get(); // Di urut dari terlama ke terbaru
        // $transaksis = $query->latest('waktu_transaksi')->get();  // Di urut dari terbaru ke terlama

        $totalSebelumnya = 0;

        $data = $transaksis->map(function ($trx) use (&$totalSebelumnya) {
            $kodeBarang = $trx->barang->produk->kode ?? $trx->barang->pendukung->kode ?? '-';
            $masuk = $trx->pemasukan_id ? $trx->jumlahRp : 0;
            $keluar = $trx->pengeluaran_id ? $trx->jumlahRp : 0;

            $totalSekarang = $totalSebelumnya + $masuk - $keluar;
            $totalSebelumnya = $totalSekarang;

            return [
                'id' => $trx->id,
                'barang_id' => $trx->barang_id,
                // 'transaksi_id' => $trx->id,
                'supplier_id' => $trx->supplier_id,
                'pemasukan_id' => $trx->pemasukan_id,
                'pengeluaran_id' => $trx->pengeluaran_id,
                'kategori' => $trx->kategori,
                'waktu' => $trx->waktu_transaksi,
                'kode_transaksi' => $trx->pemasukan->kode ?? $trx->pengeluaran->kode ?? '-',
                'kode_barang' => $kodeBarang,
                'supplier' => $trx->supplier->nama ?? '-',
                'barangs' => $trx->barang->nama_barang ?? '-',
                'nama_barang' => $trx->barang->nama_barang,
                'qty' => $trx->qtyHistori ?? 0,     // akan mengambil dari qtyHistori pada tb transaksi
                'masuk' => $masuk,
                'keluar' => $keluar,
                'total' => $totalSekarang,
                'jumlahRp' => $trx->jumlahRp,
                'satuan' => $trx->satuan,
                'waktu_transaksi' => $trx->waktu_transaksi,
            ];
        })->reverse()->values(); //akan membalik urutan perhitungan total data transaksi

        // kategori dan tipe barang
        $kategori = $request->input('kategori', 'pengeluaran'); // default ke pemasukan
        $tipe = $kategori === 'pengeluaran' ? 'pendukung' : 'produk'; // otomatis
        // $barangs = Barang::with($tipe)->whereHas($tipe)->get(); // ambil semua barang sesuai tipe
        $barangs = Barang::with(['produk', 'pendukung'])->get()->map(function ($b) {
            // Tentukan tipe berdasarkan relasi yang ada
            if ($b->produk) {
                $b->tipe = 'produk';
            } elseif ($b->pendukung) {
                $b->tipe = 'pendukung';
            } else {
                $b->tipe = null;
            }
            return $b;
        });
        $suppliers = Supplier::with(['pemasok', 'konsumen'])->get(); // ambil semua supplier
        $pemasoks = Supplier::whereHas('pemasok')->get();
        $konsumens = Supplier::whereHas('konsumen')->get();

        // dd($kategori, $tipe, $barangs->pluck('nama_barang')); // debug untuk melihat kategori, tipe, dan nama barang

        return view('operator.transaksi.index', [
            'data' => $data,
            'barangs' => $barangs, 
            'suppliers' => $suppliers, 
            'pemasoks' => $pemasoks,
            'konsumens' => $konsumens,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:pemasukan,pengeluaran',
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'qtyHistori' => 'required|numeric|min:1',
            'jumlahRp' => 'nullable|numeric|min:0',
            'satuan' => 'required|in:ton,kg,g,liter,paket',
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);
            $kategori = $request->kategori;
            $qtyHistori = $request->qtyHistori;
            $jumlahRp = $request->jumlahRp;
            $satuan = $request->satuan;
            $waktu = Carbon::now();

            // Konversi satuan ke KG
            $qty_kg = match($satuan) {
                'ton' => $qtyHistori * 1000,
                'kg' => $qtyHistori,
                'g' => $qtyHistori / 1000,
                default => $qtyHistori // liter dan paket tidak dikonversi
            };

            // Variabel untuk ID
            $pemasukan_id = null;
            $pengeluaran_id = null;

            if ($kategori === 'pemasukan') {
                // Buat kode pemasukan
                $last = Pemasukan::latest('id')->first();
                $nextNumber = $last ? $last->id + 1 : 1;
                $kode = 'MSK' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                $pemasukan = Pemasukan::create(['kode' => $kode]);
                $pemasukan_id = $pemasukan->id;

                // Barang dikurangi
                $barang->qty -= $qty_kg;
                $barang->save();

                // Cek stok cukup
                if ($barang->qty < $qty_kg) {
                    return back()->withInput()->withErrors(['qtyHistori' => 'Stok barang tidak mencukupi.']);
                }

                // Hitung jumlahRp jika kosong
                $jumlahRp = $jumlahRp ?: ($barang->harga * $qty_kg);

            } elseif ($kategori === 'pengeluaran') {
                // Buat kode pengeluaran
                $last = Pengeluaran::latest('id')->first();
                $nextNumber = $last ? $last->id + 1 : 1;
                $kode = 'KLR' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                $pengeluaran = Pengeluaran::create(['kode' => $kode]);
                $pengeluaran_id = $pengeluaran->id;

                // Barang dikurangi
                $barang->qty += $qty_kg;
                $barang->save();

                // Hitung harga satuan untuk update barang
                // $harga_satuan = $qty_kg > 0 ? $jumlahRp / $qty_kg : 0;
                // $barang->harga = $harga_satuan;
                // $barang->save();

                if ($jumlahRp && $qty_kg > 0) {
                    $barang->harga = $jumlahRp / $qty_kg;
                    $barang->save();
                }
            }

            // Simpan transaksi
            Transaksi::create([
                'kategori' => $kategori,
                'barang_id' => $barang->id,
                'supplier_id' => $request->supplier_id,
                'pemasukan_id' => $pemasukan_id,
                'pengeluaran_id' => $pengeluaran_id,
                'qtyHistori' => $qty_kg,
                'jumlahRp' => $jumlahRp,
                'satuan' => $satuan,
                'waktu_transaksi' => $waktu,
            ]);

            DB::commit();
            return redirect()->route('operator.transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:pemasukan,pengeluaran',
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'qtyHistori' => 'required|numeric|min:1',
            'jumlahRp' => 'nullable|numeric|min:0',
            'waktu_transaksi' => 'nullable|date',
            'tipe_barang' => 'nullable|in:pendukung',
            'satuan' => 'required|in:ton,kg,g,liter,paket',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $barang = Barang::findOrFail($request->barang_id);

        // Kembalikan stok lama terlebih dahulu
        if ($transaksi->kategori === 'pengeluaran') {
            $barang->qty -= $transaksi->qtyHistori;
        } else {
            $barang->qty += $transaksi->qtyHistori;
        }

        // Konversi satuan ke kg
        $qty = $request->qtyHistori;
        $satuan = $request->satuan;
        $qty_kg = match($satuan) {
            'ton' => $qty * 1000,
            'kg' => $qty,
            'g' => $qty / 1000,
            default => $qty,
        };

        // Hitung stok baru berdasarkan kategori
        if ($request->kategori === 'pengeluaran') {
            $barang->qty += $qty_kg;
        } else {
            if ($barang->qty < $qty_kg) {
                return back()->withErrors(['qtyHistori' => 'Stok barang tidak mencukupi untuk pemasukan.']);
            }
            $barang->qty -= $qty_kg;

            // Hitung ulang harga jika jumlahRp diisi
            if ($request->jumlahRp && $qty_kg > 0) {
                $barang->harga = $request->jumlahRp / $qty_kg;
            }
        }

        $barang->save();

        // Update transaksi
        $transaksi->update([
            'barang_id' => $barang->id,
            'supplier_id' => $request->supplier_id,
            'kategori' => $request->kategori,
            'jumlahRp' => $request->jumlahRp,
            'qtyHistori' => $qty,
            'satuan' => $satuan,
            'waktu_transaksi' => $request->waktu_transaksi,
        ]);

        return redirect()->route('operator.transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('operator.transaksi.index')->with('success', 'Data transaksi berhasil dihapus.');
    }
}

