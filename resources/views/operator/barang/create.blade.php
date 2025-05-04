@php
    use Carbon\Carbon;
    $defaultExp = Carbon::now()->addYear()->toDateString();
@endphp

@extends('layouts.app_operator')

@section('content')
<h2>Tambah Barang Produk</h2>

<form action="{{ route('barang.store') }}" method="POST">
    @csrf

    <div>
        <label>Kode Produk</label>
        <input type="text" name="kode" value="{{ $newKode }}" readonly>
    </div>

    <div>
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required>
    </div>

    <div>
        <label>Qty (Stok)</label>
        <input type="number" name="qty" value="{{ old('qty') ?? 0 }}" min="0" required>
    </div>

    <div>
        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="{{ old('harga') }}" min="0" required>
    </div>

    <div>
        <label>Expired</label>
        <input type="date" name="exp" value="{{ old('exp', $defaultExp) }}" required>
    </div>

    <button type="submit">Simpan</button>
</form>
@endsection