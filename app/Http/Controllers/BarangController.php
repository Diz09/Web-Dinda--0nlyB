<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        $barangs = Barang::all();
        return view('operator.barang.index', compact('barangs'));
    }

    // Menampilkan form untuk menambah barang
    public function create()
    {
        return view('operator.barang.create');
    }

    // Menyimpan data barang baru ke database
    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer',
        ]);

        // Simpan data barang ke database
        Barang::create($request->all());

        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan');
    }

    // Menampilkan form untuk edit barang
    public function edit($id)
    {
        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Menampilkan form edit dengan data barang yang sudah ada
        return view('operator.barang.edit', compact('barang'));
    }

    // Menyimpan perubahan barang
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer',
        ]);

        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Update data barang
        $barang->update($request->all());

        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui');
    }

    // Menghapus barang
    public function destroy($id)
    {
        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Hapus data barang
        $barang->delete();

        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus');
    }
}
