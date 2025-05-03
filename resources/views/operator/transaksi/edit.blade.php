@extends('layouts.app_operator')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Transaksi</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('operator.transaksi.update', $transaksi->id) }}">
        @csrf
        @method('PUT')

        {{-- Nama Barang --}}
        <div class="mb-3">
            <label for="nama_barang">Nama Barang</label>
            <input list="daftar-barang" name="nama_barang" id="nama_barang" class="form-control" value="{{ old('nama_barang', $transaksi->barang->nama_barang ?? '') }}" required>
            <datalist id="daftar-barang">
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->nama_barang }}">
                @endforeach
            </datalist>
        </div>

        {{-- Supplier --}}
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" class="form-control" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $transaksi->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" class="form-control" id="kategori" required>
                <option value="pemasukan" {{ $transaksi->pemasukan_id ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ $transaksi->pengeluaran_id ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        {{-- Qty --}}
        <div class="mb-3">
            <label for="qty" class="form-label">Jumlah Barang (Qty)</label>
            <input type="number" name="qty" class="form-control" value="{{ old('qty', $transaksi->qtyHistori ?? 1) }}" min="1" required>
        </div>

        {{-- Tipe Barang --}}
        <div class="mb-3" id="tipe-barang-container" style="{{ $transaksi->pengeluaran_id ? '' : 'display: none;' }}">
            <label for="tipe_barang" class="form-label">Tipe Barang</label>
            <select name="tipe_barang" id="tipe_barang" class="form-control">
                <option value="mentah" {{ ($transaksi->barang->tipe ?? '') == 'mentah' ? 'selected' : '' }}>Mentah</option>
                <option value="dasar" {{ ($transaksi->barang->tipe ?? '') == 'dasar' ? 'selected' : '' }}>Dasar</option>
                <option value="produk" {{ ($transaksi->barang->tipe ?? '') == 'produk' ? 'selected' : '' }}>Produk</option>
            </select>
        </div>

        {{-- Jumlah Rp --}}
        <div class="mb-3">
            <label for="jumlahRp" class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlahRp" class="form-control" value="{{ old('jumlahRp', $transaksi->jumlahRp) }}" required>
        </div>

        {{-- Waktu --}}
        <div class="mb-3">
            <label for="waktu_transaksi" class="form-label">Waktu Transaksi</label>
            <input type="datetime-local" name="waktu_transaksi" class="form-control"
                value="{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('Y-m-d\TH:i') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('operator.transaksi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

{{-- Script untuk toggle tipe_barang --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kategori');
        const tipeContainer = document.getElementById('tipe-barang-container');

        kategoriSelect.addEventListener('change', function () {
            if (kategoriSelect.value === 'pengeluaran') {
                tipeContainer.style.display = 'block';
            } else {
                tipeContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection
