<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Pemasok;
use App\Models\Konsumen;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with(['pemasok', 'konsumen'])->get();
        
        return view('operator.supplier.index', compact('suppliers'));
    }

    // public function create()
    // {
    //     return view('operator.supplier.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'alamat' => 'required|string',
    //         'no_tlp' => 'required|string|max:20',
    //         'tipe' => 'required|in:pemasok,konsumen',
    //     ]);

    //     $supplier = Supplier::create([
    //         'nama' => $request->nama,
    //         'alamat' => $request->alamat,
    //         'no_tlp' => $request->no_tlp,
    //     ]);

    //     if ($request->tipe == 'pemasok') {
    //         Pemasok::create([
    //             'supplier_id' => $supplier->id,
    //             'kode' => 'PMS' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT),
    //         ]);
    //     } else {
    //         Konsumen::create([
    //             'supplier_id' => $supplier->id,
    //             'kode' => 'KSM' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT),
    //         ]);
    //     }

    //     return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    // }
}
