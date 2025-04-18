<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Barang;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangKeluars = BarangKeluar::with('barang')->get();
        return view('operator.barang_keluar.index', compact('barangKeluars'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('operator.barang_keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        BarangKeluar::create([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            // 'tanggal' otomatis terisi dari migration karena pakai useCurrent()
        ]);

        return redirect()->route('barangkeluar.index')->with('success', 'Data barang keluar berhasil ditambahkan');
    }
}
