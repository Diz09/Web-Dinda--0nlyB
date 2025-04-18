@extends('layouts.app_operator')

@section('title', 'Dashboard Operator')

@section('content')
    <div class="welcome-box">Dashboard</div>
    <div class="grid-container">
        <div class="box">
            <h2>Total Customer</h2>
            <p>Diagram Garis</p>
        </div>
        <div class="box">
            <h2>Pendapatan 1 Bulan</h2>
            <p>Rp 15.000.000</p>
        </div>
    </div>
@endsection

{{-- <div class="grid grid-cols-2 gap-4">
    <!-- Tabel Barang Masuk -->
    <div class="bg-blue-200 p-4 rounded">
        <h3 class="font-semibold mb-2">Barang Masuk</h3>
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangMasukTerbaru as $masuk)
                    <tr>
                        <td>{{ $masuk->tanggal->format('d M Y') }}</td>
                        <td>{{ $masuk->barang->nama }}</td>
                        <td>{{ $masuk->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tabel Barang Keluar -->
    <div class="bg-blue-200 p-4 rounded">
        <h3 class="font-semibold mb-2">Barang Keluar</h3>
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangKeluarTerbaru as $keluar)
                    <tr>
                        <td>{{ $keluar->tanggal->format('d M Y') }}</td>
                        <td>{{ $keluar->barang->nama }}</td>
                        <td>{{ $keluar->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> --}}
