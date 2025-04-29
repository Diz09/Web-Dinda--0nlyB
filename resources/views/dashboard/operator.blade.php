@extends('layouts.app_operator')

@section('title', 'Dashboard Operator')

@section('content')
    <div class="bg-purple-300 p-3 rounded text-lg font-bold mb-6">Dashboard</div>

    <!-- Box Pendapatan -->
    <div class="flex justify-end mb-6">
        <div class="bg-yellow-100 p-4 rounded shadow text-right w-64">
            <h2 class="font-semibold">Jumlah Total Barang</h2>
            <p class="text-lg font-bold">1000 items</p>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-yellow-100 p-6 rounded shadow">
        <h2 class="font-bold mb-4">Aktivitas Terbaru</h2>

        <div class="row">
            <div class="box-custom">
                <h3>Barang</h3>
                <table class="w-full text-sm bg-white rounded shadow">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="p-2 text-left">No</th>
                            <th class="p-2 text-left">Kode</th>
                            <th class="p-2 text-left">Nama Barang</th>
                            <th class="p-2 text-left">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangTerbaru as $i => $barang)
                            @php
                                $kode = '-';
                                if ($barang->mentah) {
                                    $kode = $barang->mentah->kode;
                                } elseif ($barang->dasar) {
                                    $kode = $barang->dasar->kode;
                                } elseif ($barang->produk) {
                                    $kode = $barang->produk->kode;
                                }
                            @endphp
                            <tr>
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $kode }}</td>
                                <td class="p-2">{{ $barang->nama_barang }}</td>
                                <td class="p-2">{{ $barang->qty ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('operator.barang.index') }}" class="text-blue-500 mt-2 inline-block">Selengkapnya</a>
            </div>

            <!-- Tabel Transaksi -->
            <div class="box-custom">
                <h3>Aktivitas Transaksi</h3>
                <table class="w-full text-sm bg-white rounded shadow">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="p-2 text-left">No</th>
                            <th class="p-2 text-left">Waktu</th>
                            <th class="p-2 text-left">Nama</th>
                            <th class="p-2 text-left">Kategori</th>
                            <th class="p-2 text-left">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksiTerbaru as $i => $trx)
                            <tr>
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ \Carbon\Carbon::parse($trx['waktu'])->format('d-m-Y H:i') }}</td>
                                <td class="p-2">{{ $trx['nama_barang'] }}</td>
                                <td class="p-2">{{ $trx['kategori'] }}</td>
                                <td class="p-2">{{ $trx['harga'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('operator.transaksi.index') }}" class="text-blue-500 mt-2 inline-block">Selengkapnya</a>
            </div>
        </div>

    </div>
@endsection
