@extends('layouts.app_operator')

@section('content')
<div class="container">
    <h1 class="mb-4">Transaksi</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="#" class="btn btn-primary mb-3">Tambah Transaksi</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu dan Tanggal</th>
                <th>Kode</th>
                <th>Suplier</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Qty</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>7:10 12-april-2025</td>
                <td>tr01</td>
                <td>kantor A</td>
                <td>ikanmentah</td>
                <td>Masuk</td>
                <td>50ton</td>
                <td>Rp 10000000</td>
                <td>Rp 0</td>
                <td>Rp 10000000</td>
            </tr>
            <tr>
                <td>2</td>
                <td>7:10 12-april-2025</td>
                <td>tr02</td>
                <td>kantor d</td>
                <td>produk1</td>
                <td>Keluar</td>
                <td>12ton</td>
                <td>Rp 0</td>
                <td>Rp 1200000</td>
                <td>Rp 8800000</td>
            </tr>
            <tr>
                <td>3</td>
                <td>7:10 12-april-2025</td>
                <td>tr03</td>
                <td>kantor c</td>
                <td>kayu</td>
                <td>Masuk</td>
                <td>12kg</td>
                <td>Rp 0</td>
                <td>Rp 500000</td>
                <td>Rp 8300000</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
