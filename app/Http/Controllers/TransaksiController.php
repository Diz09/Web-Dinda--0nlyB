<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\BarangDasar;
use App\Models\BarangMentah;
use App\Models\BarangProduk;
use App\Models\Supplier;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['barang.mentah', 'barang.dasar', 'barang.produk', 'supplier', 'pemasukan', 'pengeluaran']);

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
            $kodeBarang = $trx->barang->mentah->kode ?? $trx->barang->dasar->kode ?? $trx->barang->produk->kode ?? '-';
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
        $tipe = $request->input('tipe_barang', 'mentah');

        $suppliers = Supplier::all();
        $barangs = collect();

        if ($kategori === 'pemasukan') {
            $barangs = Barang::whereHas('produk')
                ->with(['produk' => function ($query) {
                    $query->select('id', 'barang_id', 'kode');
                }])
                ->get();
        } else {
            if ($tipe === 'dasar') {
                $barangs = Barang::whereHas('dasar')
                    ->with(['dasar' => function ($query) {
                        $query->select('id', 'barang_id', 'kode');
                    }])
                    ->get();
            } else {
                $barangs = Barang::whereHas('mentah')
                    ->with(['mentah' => function ($query) {
                        $query->select('id', 'barang_id', 'kode');
                    }])
                    ->get();
            }
        }

        return view('operator.transaksi.create', compact('barangs', 'suppliers', 'kategori', 'tipe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:pemasukan,pengeluaran',
            'supplier_id' => 'required|exists:suppliers,id',
            'qty' => 'required|numeric|min:1',  // qty histori dan stok
            'jumlahRp' => 'nullable|numeric|min:1',
            'waktu_transaksi' => 'nullable|date',
            'nama_barang' => 'required|string|max:255',
            'tipe_barang' => 'nullable|in:mentah,dasar',
        ]);

        $barangNama = trim(preg_replace('/\s*\(.*?\)$/', '', $request->nama_barang));
        $barang = Barang::where('nama_barang', $barangNama)->first();

        if (!$barang) {
            // Barang baru
            $barang = Barang::create([
                'nama_barang' => $barangNama,
                'qty' => 0,
                'exp' => null,
                'harga' => 0,
            ]);

            // Relasi barang mentah/dasar jika kategori pengeluaran
            if ($request->kategori === 'pengeluaran') {
                if ($request->tipe_barang === 'mentah') {
                    BarangMentah::create([
                        'barang_id' => $barang->id,
                        'kode' => 'MNT' . str_pad(BarangMentah::count() + 1, 3, '0', STR_PAD_LEFT),
                    ]);
                } elseif ($request->tipe_barang === 'dasar') {
                    BarangDasar::create([
                        'barang_id' => $barang->id,
                        'kode' => 'DSR' . str_pad(BarangDasar::count() + 1, 3, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        // ✅ Logika stok TERBALIK karena dilihat dari sisi transaksi
        if ($request->kategori === 'pengeluaran') {
            // uang keluar -> beli barang -> stok barang bertambah
            $barang->qty += $request->qty;
        } else {
            // uang masuk -> barang dijual/terpakai -> stok barang berkurang
            if ($barang->qty < $request->qty) {
                return back()->withErrors(['qty' => 'Stok barang tidak mencukupi untuk pemasukan.']);
            }
            $barang->qty -= $request->qty;

            // opsional update harga
            if ($request->jumlahRp) {
                $barang->harga = $request->jumlahRp;
            }
        }

        $barang->save();

        // Buat pemasukan/pengeluaran
        if ($request->kategori === 'pemasukan') {
            $kode = 'MSK' . str_pad(Pemasukan::count() + 1, 3, '0', STR_PAD_LEFT);
            $pemasukan = Pemasukan::create(['kode' => $kode]);
            $pemasukanId = $pemasukan->id;
            $pengeluaranId = null;
        } else {
            $kode = 'KLR' . str_pad(Pengeluaran::count() + 1, 3, '0', STR_PAD_LEFT);
            $pengeluaran = Pengeluaran::create(['kode' => $kode]);
            $pengeluaranId = $pengeluaran->id;
            $pemasukanId = null;
        }

        // ✅ Tambahkan qtyHistori ke dalam transaksi
        Transaksi::create([
            'barang_id' => $barang->id,
            'supplier_id' => $request->supplier_id,
            'pemasukan_id' => $pemasukanId,
            'pengeluaran_id' => $pengeluaranId,
            'jumlahRp' => $request->jumlahRp ?? 0,
            'qtyHistori' => $request->qty, // <- ini ditambahkan
            'waktu_transaksi' => $request->waktu_transaksi ?? now(),
        ]);

        return redirect()->route('operator.transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
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
            'tipe_barang' => 'nullable|in:mentah,dasar',
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
                if ($request->tipe_barang === 'mentah') {
                    BarangMentah::create([
                        'barang_id' => $barang->id,
                        'kode' => 'MNT' . str_pad(BarangMentah::count() + 1, 3, '0', STR_PAD_LEFT),
                    ]);
                } elseif ($request->tipe_barang === 'dasar') {
                    BarangDasar::create([
                        'barang_id' => $barang->id,
                        'kode' => 'DSR' . str_pad(BarangDasar::count() + 1, 3, '0', STR_PAD_LEFT),
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

