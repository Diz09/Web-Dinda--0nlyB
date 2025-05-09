<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangProduk;
use App\Models\BarangDasar;
use App\Models\BarangMentah;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $barangs = Barang::with(['produk', 'mentah', 'dasar'])->get();

        if ($filter) {
            $barangs = $barangs->filter(function ($barang) use ($filter) {
                return $barang->{$filter};
            });
        }

        // $barangs = $query->get();

        return view('operator.barang.index', compact('barangs', 'filter'));
    }

    public function create()
    {
        // Ambil kode terakhir dari barangproduk
        // $lastKode = \App\Models\BarangProduk::orderBy('id', 'desc')->first();

        // // Ambil angka terakhir, jika ada
        // $lastNumber = $lastKode ? (int)substr($lastKode->kode, 3) : 0;

        // Format kode baru
        $newKode = $this->generateKodeBaru('produk');

        return view('operator.barang.create', compact('newKode'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'exp' => 'required|date|after:today',
        ]);

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'qty' => $request->qty,
            'harga' => $request->harga,
            'exp' => $request->exp,
        ]);

        // Tambahkan sebagai produk dengan kode auto
        $kodeBaru = $this->generateKodeBaru('produk');

        BarangProduk::create([
            'barang_id' => $barang->id,
            'kode' => $kodeBaru,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang produk berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $barang = Barang::with(['produk', 'mentah', 'dasar'])->findOrFail($id);
        return view('operator.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::with(['produk', 'mentah', 'dasar'])->findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:produk,mentah,dasar',
            'exp' => 'nullable|date',
            'harga' => 'required|numeric|min:0',
        ]);

        $kategori_lama = $barang->produk ? 'produk' : ($barang->mentah ? 'mentah' : 'dasar');
        $kategori_baru = $request->kategori;

        // Cek apakah melibatkan 'produk'
        $melibatkanProduk = $kategori_lama === 'produk' || $kategori_baru === 'produk';

        // Jika kategori berubah dan melibatkan produk, tapi tidak ada konfirmasi, maka tolak
        if ($kategori_lama !== $kategori_baru && $melibatkanProduk && !$request->has('confirm_produk')) {
            return back()->with('error', 'Perpindahan kategori ke/dari Produk perlu konfirmasi.');
        }

        // Update barang utama
        $barang->nama_barang = $request->nama_barang;
        $barang->exp = $request->exp;
        $barang->harga = $request->harga;
        $barang->save();

        // Hapus relasi lama
        if ($kategori_lama === 'mentah' && $barang->mentah) {
            $barang->mentah->delete();
        } elseif ($kategori_lama === 'dasar' && $barang->dasar) {
            $barang->dasar->delete();
        } elseif ($kategori_lama === 'produk' && $barang->produk) {
            $barang->produk->delete();
        }

        // Tambah relasi baru sesuai kategori
        $kodeBaru = $this->generateKodeBaru($kategori_baru);

        if ($kategori_baru === 'mentah') {
            BarangMentah::create([
                'barang_id' => $barang->id,
                'kode' => $kodeBaru,
            ]);
        } elseif ($kategori_baru === 'dasar') {
            BarangDasar::create([
                'barang_id' => $barang->id,
                'kode' => $kodeBaru,
            ]);
        } elseif ($kategori_baru === 'produk') {
            BarangProduk::create([
                'barang_id' => $barang->id,
                'kode' => $kodeBaru,
            ]);
        }

        return redirect()->route('barang.index', ['filter' => $kategori_baru])
                        ->with('success', 'Barang berhasil diperbarui.');
    }

    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->qty = $request->qty;
        $barang->save();

        return back()->with('success', 'Stok barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::with(['produk', 'mentah', 'dasar'])->findOrFail($id);

        if ($barang->produk) {
            $barang->produk->delete();
        } elseif ($barang->mentah) {
            $barang->mentah->delete();
        } elseif ($barang->dasar) {
            $barang->dasar->delete();
        }

        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }

    public function stokPimpinan()
    {
        $barang = Barang::with(['mentah', 'dasar', 'produk'])->get();
        return view('pimpinan.stock_barang.index', compact('barang'));
    }

    private function generateKodeBaru($kategori)
    {
        switch ($kategori) {
            case 'produk':
                $prefix = 'PRD';
                $model = BarangProduk::class;
                break;
            case 'mentah':
                $prefix = 'MNT';
                $model = BarangMentah::class;
                break;
            case 'dasar':
                $prefix = 'DSR';
                $model = BarangDasar::class;
                break;
            default:
                throw new \Exception("Kategori tidak valid");
        }

        $lastKode = $model::orderBy('id', 'desc')->first();
        $lastNumber = $lastKode ? (int)substr($lastKode->kode, 3) : 0;
        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
