@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Data Gaji Harian Karyawan</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('presensi.create') }}" class="btn btn-primary mb-3">Tambah Presensi</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Tanggal Bekerja</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Gaji Per Jam</th>
                    <th>Gaji Lembur</th>
                    <th>Total Gaji Harian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensis as $presensi)
                    <tr>
                        <td>{{ $presensi->karyawan->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $presensi->jam_masuk }}</td>
                        <td>{{ $presensi->jam_pulang }}</td>
                        <td>Rp {{ number_format($presensi->karyawan->gaji_per_jam, 0, ',', '.') }}</td>
                        <td>
                            @if($presensi->gaji)
                                Rp {{ number_format($presensi->gaji->gaji_lembur, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($presensi->gaji)
                                Rp {{ number_format($presensi->gaji->total_gaji, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data presensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
