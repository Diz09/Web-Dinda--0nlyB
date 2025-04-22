@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Data Gaji Harian Karyawan</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('tonikan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
        </div>
        <div class="mb-3">
            <label for="jumlah_ton">Jumlah Ton Ikan</label>
            <input type="number" name="jumlah_ton" class="form-control" value="{{ old('jumlah_ton', $jumlahTonHariIni) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>    

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi Masuk</th>
                    <th>Jam Masuk</th>
                    <th>Aksi Pulang</th>
                    <th>Jam Pulang</th>
                    <th>Gaji</th>
                    <th>Aksi Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawans as $i => $k)
                @php
                    $p = $presensis->get($k->id); // cek apakah karyawan ini sudah presensi
                @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $k->nama }}</td>
                    <td>{{ $k->jenis_kelamin }}</td>
                    <td>
                        @if(!$p || !$p->jam_masuk)
                            <form action="{{ route('presensi.masuk', $k->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-success">✓ Masuk</button>
                            </form>
                        @else
                            <span class="text-success">✓</span>
                        @endif
                    </td>
                    <td>{{ $p->jam_masuk ?? '-' }}</td>
                    
                    <td>
                        @if($p && !$p->jam_pulang)
                            <form action="{{ route('presensi.pulang', $k->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-warning">✓ Pulang</button>
                            </form>
                        @elseif($p)
                            <span class="text-warning">✓</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $p->jam_pulang ?? '-' }}</td>                    
                    <td>Rp {{ number_format($p->gaji->total_gaji ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @if($p && $p->gaji)
                        <form action="{{ route('gaji.lunas', $p->gaji->id) }}" method="POST">
                            @csrf
                            <input type="number" name="dibayar" value="{{ $p->gaji->total_gaji }}" class="form-control form-control-sm" />
                            <button class="btn btn-sm btn-primary mt-1">Bayar</button>
                        </form>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>        
    </div>
</div>
@endsection
