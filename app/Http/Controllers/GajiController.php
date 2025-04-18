<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gaji;

class GajiController extends Controller
{
    public function index()
    {
        $Gaji = Gaji::with('Karyawan')->get();
        return view('operator.gaji.index', compact('gaji'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('operator.gaji.create', compact('gaji'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gaji_id' => 'required|exists:gaji,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        Gaji::create([
            'gaji_id' => $request-gaji_id,
            'jumlah' => $request->jumlah,
            // 'tanggal' otomatis terisi dari migration karena pakai useCurrent()
        ]);

        return redirect()->route('gaji.index')->with('success', 'Data Gaji berhasil ditambahkan');
    }
}
