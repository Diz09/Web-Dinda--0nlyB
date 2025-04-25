@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Detail Gaji</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Pekerja</th>
                    <th>Jenis Kelamin</th>
                    @foreach ($tanggalUnik as $tanggal)
                        <th>{{ \Carbon\Carbon::parse($tanggal)->format('d-M-y') }}</th>
                    @endforeach
                    <th>Total Jam Kerja</th>
                    <th>Gaji Per Jam</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKaryawan as $karyawanId => $presensis)
                    @php
                        $karyawan = $presensis->first()->karyawan;
                        $totalJam = 0;
                    @endphp
                    <tr>
                        <td>{{ $karyawan->nama }}</td>
                        <td>{{ $karyawan->jenis_kelamin }}</td>
        
                        @foreach ($tanggalUnik as $tanggal)
                            @php
                                $presensiTanggal = $presensis->where('tanggal', $tanggal)->first();
                                if ($presensiTanggal) {
                                    $jamMasuk = strtotime($presensiTanggal->jam_masuk);
                                    $jamPulang = strtotime($presensiTanggal->jam_pulang);
                                    $jamKerja = ($jamPulang - $jamMasuk) / 3600;
                                } else {
                                    $jamKerja = 0;
                                }
                                $totalJam += $jamKerja;
                            @endphp
                            <td>{{ number_format($jamKerja, 2) }}</td>
                        @endforeach
        
                        <td>{{ number_format($totalJam, 2) }}</td>
                        <td>{{ number_format($gajiPerJam, 0, ',', '.') }}</td>
                        <td>{{ number_format($gajiPerJam * $totalJam, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection