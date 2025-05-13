<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangProduk;
use App\Models\BarangPendukung;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $barangs = Barang::with(['produk', 'pendukung'])->get();

        // Filter hanya jika $filter ada dan valid
        if (in_array($filter, ['produk', 'pendukung'])) {
            $barangs = $barangs->filter(function ($barang) use ($filter) {
                return $barang->{$filter}; // hanya tampilkan yang memiliki relasi produk/pendukung
            });
            $newKode = $this->generateKodeBaru($filter);
        } else {
            // Jika tidak ada filter atau tidak valid, tampilkan semua data dan jangan generate kode
            $newKode = null;
        }

        // Urutkan: produk (kode PRD) lebih dulu, lalu pendukung (kode PND)
        $barangs = $barangs->sortBy(function ($barang) {
        if ($barang->produk) {
            return '1' . $barang->produk->kode; // angka kecil = prioritas lebih tinggi
        } elseif ($barang->pendukung) {
            return '2' . $barang->pendukung->kode;
        } else {
            return '9'; // untuk jaga-jaga
        }
    })->values();

        return view('operator.barang.index', compact('barangs', 'filter', 'newKode'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'exp' => 'required|date|after:today',
            'filter' => 'required|in:produk,pendukung'
        ]);

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'qty' => $request->qty,
            'harga' => $request->harga,
            'exp' => $request->exp,
        ]);

        // Tambahkan sebagai produk dengan kode auto
        $kodeBaru = $this->generateKodeBaru($request->filter);

        if ($request->filter === 'produk') {
            BarangProduk::create([
                'barang_id' => $barang->id,
                'kode' => $kodeBaru,
            ]);
        } else {
            BarangPendukung::create([
                'barang_id' => $barang->id,
                'kode' => $kodeBaru,
            ]);
        }

        // return redirect()->route('barang.index')->with('success', 'Barang produk berhasil ditambahkan');
        // return redirect()->route('barang.index')->with('success', 'Barang ' . $request->filter . ' berhasil ditambahkan');
        return redirect()->route('barang.index', ['filter' => $request->filter])
            ->with('success', 'Barang ' . $request->filter . ' berhasil ditambahkan');

    }
    
    public function update(Request $request, $id)
    {
        $barang = Barang::with(['produk', 'pendukung'])->findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:produk,pendukung',
            'exp' => 'nullable|date',
            'harga' => 'required|numeric|min:0',
        ]);

        $kategori_lama = $barang->produk ? 'produk' : 'pendukung';
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
        if ($kategori_lama === 'pendukung' && $barang->pendukung) {
            $barang->pendukung->delete();
        } elseif ($kategori_lama === 'produk' && $barang->produk) {
            $barang->produk->delete();
        }

        // Tambah relasi baru sesuai kategori
        $kodeBaru = $this->generateKodeBaru($kategori_baru);

        if ($kategori_baru === 'produk') {
            BarangProduk::create([
                'barang_id' => $barang->id,
                'kode' => $kodeBaru,
            ]);
        } else {
            BarangPendukung::create([
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
        $barang = Barang::with(['produk', 'pendukung'])->findOrFail($id);

        if ($barang->produk) {
            $barang->produk->delete();
        } elseif ($barang->pendukung) {
            $barang->pendukung->delete();
        }

        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }

    public function stokPimpinan()
    {
        $barang = Barang::with(['produk', 'pendukung'])->get();
        return view('pimpinan.stock_barang.index', compact('barang'));
    }

    private function generateKodeBaru($kategori)
    {
        switch ($kategori) {
            case 'produk':
                $prefix = 'PRD';
                $model = BarangProduk::class;
                break;
            case 'pendukung':
                $prefix = 'PDN';
                $model = BarangPendukung::class;
                break;
            default:
                throw new \Exception("Kategori tidak valid");
        }

        $lastKode = $model::orderBy('id', 'desc')->first();
        $lastNumber = $lastKode ? (int)substr($lastKode->kode, 3) : 0;
        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
