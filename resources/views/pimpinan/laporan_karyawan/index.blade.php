@extends('layouts.app_pimpinan')

@section('title', 'Laporan Karyawan')

@section('content')
<div class="container">
    <h1 class="mb-4">Data Karyawan</h1>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pekerja</th>
                <th>Jam Kerja</th>
                <th>Jam Lembur</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->jam_kerja }} jam</td>
                    <td>{{ $data->jam_lembur }} jam</td>
                    <td>Rp {{ number_format($data->total_gaji, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
