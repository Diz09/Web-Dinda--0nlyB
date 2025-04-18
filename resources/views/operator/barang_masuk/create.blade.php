@extends('layouts.app_operator')

@section('content')
<div class="container">
    <h2>Tambah Barang Masuk</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barangmasuk.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="barang_id">Nama Barang</label>
            <select name="barang_id" id="barang_id" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Masuk</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal Masuk</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection
