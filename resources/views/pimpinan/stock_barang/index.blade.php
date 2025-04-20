@extends('layouts.app_pimpinan')

@section('title', 'Laporan Karyawan')

@section('content')
<div class="container">
    <h1 class="mb-4">Stok Barang</h1>
    
    <table class="table table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barang as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ $item->stok }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
