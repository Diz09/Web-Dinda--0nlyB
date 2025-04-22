@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Rekap Gaji Karyawan</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="mulai" class="form-control" value="{{ request('mulai') }}">
        </div>
        <div class="col-md-3">
            <label>Tanggal Berakhir</label>
            <input type="date" name="akhir" class="form-control" value="{{ request('akhir') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    @if(count($karyawans))
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    @foreach($tanggalRange as $tanggal)
                        <th>{{ \Carbon\Carbon::parse($tanggal)->format('d-M') }}</th>
                    @endforeach
                    <th>Total Jam</th>
                    <th>Gaji / Jam</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawans as $i => $k)
                @php
                    $totalJam = 0;
                    $totalGaji = 0;
                    $jumlahHariAktif = 0;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $k->nama }}</td>
                    <td>{{ $k->jenis_kelamin }}</td>

                    @foreach($tanggalRange as $tanggal)
                        @php
                            $tanggalStr = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
                            $presensi = $k->presensis->where('tanggal', $tanggalStr)->first();
                            $jam = optional($presensi->gaji)->total_jam ?? 0;
                            $totalJam += $jam;
                            $totalGaji += optional($presensi->gaji)->gaji_pokok ?? 0;
                            if ($jam > 0) $jumlahHariAktif++;
                        @endphp
                        <td>{{ $jam }}</td>
                    @endforeach

                    <td>{{ number_format($totalJam, 2) }}</td>
                    <td>Rp {{ number_format($jumlahHariAktif > 0 ? $totalGaji / $jumlahHariAktif : 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="alert alert-info">Silakan pilih rentang tanggal untuk menampilkan rekap gaji.</div>
    @endif
</div>
@endsection
