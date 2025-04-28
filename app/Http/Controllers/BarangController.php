<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        // $barangs = Barang::all();
        $barangs = Barang::with(['mentah', 'dasar', 'produk'])->get();

        return view('operator.barang.index', compact('barangs'));
    }

    public function stokPimpinan()
    {
        $barang = Barang::with(['mentah', 'dasar', 'produk'])->get();
        return view('pimpinan.stock_barang.index', compact('barang'));
    }
}
