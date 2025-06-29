<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }
        .ttd {
            width: 100%;
            margin-top: 60px;
            text-align: right;
        }
        .ttd p {
            margin-bottom: 70px;
        }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Laporan Transaksi Keuangan</h3>
    <p>Periode: {{ $tanggalMulai ?? '-' }} s/d {{ $tanggalAkhir ?? '-' }}</p>
    <p>Filter Kata Kunci: {{ $q ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Kode Transaksi</th>
                <th>Kode Barang</th>
                <th>Nama Mitra</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx['waktu'])->format('d-m-Y H:i') }}</td>
                    <td>{{ $trx['kode_transaksi'] }}</td>
                    <td>{{ $trx['kode_barang'] }}</td>
                    <td>{{ $trx['supplier'] }}</td>
                    <td>{{ $trx['nama_barang'] }}</td>
                    <td>{{ $trx['qty'] }}</td>
                    <td>Rp {{ number_format($trx['masuk'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($trx['keluar'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($trx['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    @foreach($data as $i => $trx)
    <p>
        @if($loop->last)
            Transaksi terakhir dilakukan pada tanggal {{ \Carbon\Carbon::parse($trx['waktu'])->format('d-m-Y H:i') }} sebanyak Rp {{ number_format($trx['total'], 0, ',', '.') }}.
        @endif
    </p>
    @endforeach

    <br><br>
    <div style="text-align: right;">
        <p>Mengetahui,</p>
        <p style="margin-top: 60px;">Poniman</p>
        <p>....................................</p>
    </div>
</body>
</html>