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
        $query = Transaksi::with(['barang.produk', 'barang.pendukung', 'supplier', 'pemasukan', 'pengeluaran']);

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
                'waktu' => $trx->waktu_transaksi,
                'kode_transaksi' => $trx->pemasukan->kode ?? $trx->pengeluaran->kode ?? '-',
                'kode_barang' => $kodeBarang,
                'supplier' => $trx->supplier->nama ?? '-',
                'nama_barang' => $trx->barang->nama_barang,
                'qty' => $trx->qtyHistori ?? 0,     // akan mengambil dari qtyHistori pada tb transaksi
                'masuk' => $masuk,
                'keluar' => $keluar,
                'total' => $totalSekarang,
            ];
        })->reverse()->values(); //akan membalik urutan perhitungan total data transaksi

        return view('operator.transaksi.index', compact('data'));
    }

    public function create(Request $request)
    {
        $kategori = $request->input('kategori', 'pemasukan');
        $tipe = $kategori === 'pengeluaran' ? 'pendukung' : 'produk'; // otomatis

        $suppliers = Supplier::with(['pemasok', 'konsumen'])->get(); // pastikan relasi di-load
        $barangs = collect();

        if ($kategori === 'pemasukan') {
            $barangs = Barang::whereHas('produk')->with('produk')->get();
        } else {
            $barangs = Barang::whereHas('pendukung')->with('pendukung')->get();
        }

        return view('operator.transaksi.create', compact('barangs', 'suppliers', 'kategori', 'tipe'));
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
            $qty = $request->qtyHistori;
            $jumlahRp = $request->jumlahRp;
            $satuan = $request->satuan;
            $waktu = Carbon::now();

            // Konversi satuan ke KG
            $qty_kg = match($satuan) {
                'ton' => $qty * 1000,
                'kg' => $qty,
                'g' => $qty / 1000,
                default => $qty // liter dan paket tidak dikonversi
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
                $barang->qty -= $qty;
                $barang->save();

                // Hitung jumlahRp jika kosong
                $jumlahRp = $jumlahRp ?: ($barang->harga * $qty);

            } elseif ($kategori === 'pengeluaran') {
                // Buat kode pengeluaran
                $last = Pengeluaran::latest('id')->first();
                $nextNumber = $last ? $last->id + 1 : 1;
                $kode = 'KLR' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                $pengeluaran = Pengeluaran::create(['kode' => $kode]);
                $pengeluaran_id = $pengeluaran->id;

                // Barang dikurangi
                $barang->qty += $qty;
                $barang->save();

                // Hitung harga satuan untuk update barang
                $harga_satuan = $qty_kg > 0 ? $jumlahRp / $qty_kg : 0;
                $barang->harga = $harga_satuan;
                $barang->save();
            }

            // Simpan transaksi
            Transaksi::create([
                'kategori' => $kategori,
                'barang_id' => $barang->id,
                'supplier_id' => $request->supplier_id,
                'pemasukan_id' => $pemasukan_id,
                'pengeluaran_id' => $pengeluaran_id,
                'qtyHistori' => $qty,
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


    public function edit($id)
    {
        $transaksi = Transaksi::with('barang')->findOrFail($id);
        $barangs = Barang::all();
        $suppliers = Supplier::all();

        return view('operator.transaksi.edit', compact('transaksi', 'barangs', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:pemasukan,pengeluaran',
            'supplier_id' => 'required|exists:suppliers,id',
            'qty' => 'required|numeric|min:1',
            'jumlahRp' => 'nullable|numeric|min:1',
            'waktu_transaksi' => 'nullable|date',
            'nama_barang' => 'required|string|max:255',
            'tipe_barang' => 'nullable|in:pendukung',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // Ambil barang (tanpa kode di datalist)
        $barangNama = trim(preg_replace('/\s*\(.*?\)$/', '', $request->nama_barang));
        $barang = Barang::where('nama_barang', $barangNama)->first();

        if (!$barang) {
            $barang = Barang::create([
                'nama_barang' => $barangNama,
                'qty' => 0,
                'exp' => null,
                'harga' => 0,
            ]);

            if ($request->kategori === 'pengeluaran') {
                if ($request->tipe_barang === 'pendukung') {
                    BarangPendukung::create([
                        'barang_id' => $barang->id,
                        'kode' => 'MNT' . str_pad(BarangPendukung::count() + 1, 3, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        // Revisi stok: Kembalikan stok lama dahulu
        if ($transaksi->kategori === 'pengeluaran') {
            $barang->qty -= $transaksi->qty; // sebelumnya stok ditambah
        } else {
            $barang->qty += $transaksi->qty; // sebelumnya stok dikurangi
        }

        // Sekarang hitung ulang berdasarkan update
        if ($request->kategori === 'pengeluaran') {
            $barang->qty += $request->qty;
        } else {
            if ($barang->qty < $request->qty) {
                return back()->withErrors(['qty' => 'Stok barang tidak mencukupi untuk pemasukan.']);
            }
            $barang->qty -= $request->qty;

            if ($request->jumlahRp) {
                $barang->harga = $request->jumlahRp;
            }
        }

        $barang->save();

        // Update relasi transaksi
        $transaksi->update([
            'barang_id' => $barang->id,
            'supplier_id' => $request->supplier_id,
            'kategori' => $request->kategori,
            'jumlahRp' => $request->jumlahRp,
            'waktu_transaksi' => $request->waktu_transaksi,
            'qty' => $request->qty,
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

