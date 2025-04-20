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
                            <th class="p-2 text-left">Nama</th>
                            <th class="p-2 text-left">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangTerbaru as $i => $b)
                            <tr>
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $b->kode }}</td>
                                <td class="p-2">{{ $b->nama }}</td>
                                <td class="p-2">{{ $b->stok }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('barang.index') }}" class="text-blue-500 mt-2 inline-block">Selengkapnya</a>
            </div>

            <!-- Tabel Transaksi -->
            <div class="box-custom">
                <h3>Aktivitas Transaksi</h3>
                <table class="w-full text-sm bg-white rounded shadow">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="p-2 text-left">No</th>
                            <th class="p-2 text-left">Tanggal</th>
                            <th class="p-2 text-left">Nama</th>
                            <th class="p-2 text-left">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksiTerbaru as $i => $trx)
                            <tr>
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $trx->tanggal_transaksi }}</td>
                                <td class="p-2">{{ $trx->aktifitas }}</td>
                                <td class="p-2">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="#{{-- {{ route('transaksi.index') }} --}}" class="text-blue-500 mt-2 inline-block">Selengkapnya</a>
            </div>
        </div>

    </div>
@endsection
