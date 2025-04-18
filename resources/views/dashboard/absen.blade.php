@extends('layouts.app')

@section('title', 'Data Absen')

@section('content')
<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Absen</h2>
        <div class="tanggal">{{ \Carbon\Carbon::now()->format('d F Y') }} ▶</div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nama</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Gaji / Jam</th>
                <th>Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absens as $absen)
                <tr>
                    <td>{{ $absen->nama }}</td>
                    <td>{{ $absen->jam_masuk }}</td>
                    <td>{{ $absen->jam_pulang }}</td>
                    <td>{{ $absen->total_jam }}</td>
                    <td>{{ number_format($absen->gaji_per_jam, 0, ',', '.') }}</td>
                    <td>{{ number_format($absen->gaji, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('absen.create') }}" class="btn btn-primary mt-3">Tambah</a>
</div>
@endsection
