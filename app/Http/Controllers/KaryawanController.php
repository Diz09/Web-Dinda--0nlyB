<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::all();
        return view('operator.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('operator.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'gaji_per_jam' => 'required|numeric|min:0',
        ]);

        Karyawan::create($request->only(['nama', 'jabatan', 'gaji_per_jam']));

        return redirect()->route('operator.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('operator.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'gaji_per_jam' => 'required|numeric|min:0',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->only(['nama', 'jabatan', 'gaji_per_jam']));

        return redirect()->route('operator.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('operator.karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
