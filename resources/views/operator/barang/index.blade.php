@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Data Barang</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Exp</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $i => $barang)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $barang->kategori->kode_prefix . 0 . $barang->id ?? '-' }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->qty }}</td>
                    <td>{{ $barang->exp ? \Carbon\Carbon::parse($barang->exp)->format('d-m-Y') : '-' }}</td>
                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
