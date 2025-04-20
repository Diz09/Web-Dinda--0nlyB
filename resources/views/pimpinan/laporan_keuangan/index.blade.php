@extends('layouts.app_pimpinan')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container">
    <h1 class="mb-4">Laporan Keuangan</h1>
    
    <table class="table table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Aktifitas</th>
                <th>Jenis</th>
                <th>Keluar</th>
                <th>Masuk</th>
                <th>Sisa Uang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $saldo = 0;
            @endphp
            @foreach ($keuangan as $index => $data)
                @php
                    if ($data->jenis === 'Masuk') {
                        $saldo += $data->jumlah;
                    } elseif ($data->jenis === 'Keluar') {
                        $saldo -= $data->jumlah;
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td>{{ $data->aktifitas }}</td>
                    <td>{{ $data->jenis }}</td>
                    <td>
                        @if ($data->jenis === 'Keluar')
                            Rp {{ number_format($data->jumlah, 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                    <td>
                        @if ($data->jenis === 'Masuk')
                            Rp {{ number_format($data->jumlah, 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                    <td>Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection