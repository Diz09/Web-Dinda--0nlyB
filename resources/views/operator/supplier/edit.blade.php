<!-- resources/views/operator/barang/edit.blade.php -->

@extends('layouts.app_operator')

@section('content')
    <div class="container">
        <h2>Edit Barang</h2>

        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nama">Nama Barang</label>
                <input type="text" class="form-control" name="nama" id="nama" value="{{ old('nama', $barang->nama) }}" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori Barang</label>
                <input type="text" class="form-control" name="kategori" id="kategori" value="{{ old('kategori', $barang->kategori) }}" required>
            </div>

            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" class="form-control" name="stok" id="stok" value="{{ old('stok', $barang->stok) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Barang</button>
        </form>
    </div>
@endsection
