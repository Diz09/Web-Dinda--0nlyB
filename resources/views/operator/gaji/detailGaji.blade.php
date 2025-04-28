@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Detail Gaji {{ $kuartal->nama_kuartal }}</h3>
    
    <form method="POST" action="{{ route('presensi.tonikan.store') }}" class="mb-4">
        @csrf
        <input type="hidden" name="kuartal_id" value="{{ $kuartal }}">
        
        <div class="mb-3">
            <label for="jumlah_ton">Jumlah Ton Ikan (ton)</label>
            <input type="number" name="jumlah_ton" class="form-control" value="{{ old('jumlah_ton', $jumlahTonHariIni) }}" required>
        </div>
        
        <div class="mb-3">
            <label for="harga_ikan_per_ton">Harga Ikan Per Ton (Rp)</label>
            <input type="number" name="harga_ikan_per_ton" class="form-control" value="{{ old('harga_ikan_per_ton', $hargaIkanPerTon) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Data Ton Ikan</button>
    </form>

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