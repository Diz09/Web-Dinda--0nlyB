<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    // Menampilkan semua pengeluaran
    public function index()
    {
        $pengeluarans = Pengeluaran::all();
        return view('pengeluaran.index', compact('pengeluarans'));
    }

    // Menampilkan form tambah pengeluaran
    public function create()
    {
        return view('pengeluaran.create');
    }

    // Menyimpan data pengeluaran baru
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'nullable|string|max:255',
            'jumlah' => 'nullable|numeric',
            'tanggal' => 'required|date',
        ]);

        Pengeluaran::create($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // Menampilkan form edit pengeluaran
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    // Menyimpan perubahan pengeluaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'nullable|string|max:255',
            'jumlah' => 'nullable|numeric',
            'tanggal' => 'required|date',
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    // Menghapus pengeluaran
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
