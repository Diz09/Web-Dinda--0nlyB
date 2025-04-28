<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TonIkan;

class TonIkanController extends Controller
{
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'jumlah_ton' => 'required|numeric|min:0',
    //     ]);

    //     // Simpan ke database (pastikan model dan tabelnya ada)
    //     \App\Models\TonIkan::create([
    //         'jumlah_ton' => $request->jumlah_ton,
    //         'tanggal' => now(),
    //     ]);

    //     return redirect()->back()->with('success', 'Data ton ikan berhasil disimpan.');
    // }

    // public function simpanJumlahTon(Request $request)
    // {
    //     $tanggal = $request->input('tanggal'); // format: Y-m-d
    //     $jumlahTon = $request->input('jumlah_ton');

    //     DB::table('ton_ikan')->updateOrInsert(
    //         ['tanggal' => $tanggal],
    //         [
    //             'jumlah_ton' => $jumlahTon,
    //             'updated_at' => now(),
    //             'created_at' => now()
    //         ]
    //     );

    //     return redirect()->back()->with('success', 'Jumlah ton berhasil disimpan.');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah_ton' => 'required|numeric|min:0',
            'harga_ikan_per_ton' => 'required|numeric|min:0',
        ]);

        TonIkan::updateOrCreate(
            ['tanggal' => $request->tanggal],
            ['jumlah_ton' => $request->jumlah_ton],
            ['harga_ikan_per_ton' => $request->harga_ikan_per_ton]
        );

        return redirect()->back()->with('success', 'Jumlah ton ikan berhasil disimpan.');
    }

}
