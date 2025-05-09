@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Presensi Harian Pekerja</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="formKuartal" class="mb-3">
        <label for="kuartal_id">Pilih Kuartal</label>
        <div class="input-group">
            <select name="kuartal_id" id="kuartal_id" class="form-control">
                @foreach($kuartals as $k)
                    <option value="{{ $k->id }}">
                        {{ $k->nama_kuartal }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-outline-primary" id="btnLihatKuartal">Lihat</button>
        </div>
    </form>
    
    <script>
        document.getElementById('btnLihatKuartal').addEventListener('click', function () {
            const select = document.getElementById('kuartal_id');
            const id = select.value;
    
            if (id) {
                window.location.href = `/operator/gaji/${id}`;
            } else {
                alert('Pilih kuartal terlebih dahulu');
            }
        });
    </script>
    
    {{-- Tombol Buat Kuartal Baru --}}
    <form method="GET" class="mb-4">
        <input type="hidden" name="buat_kuartal" value="1">
        <button class="btn btn-success">+ Buat Kuartal Baru</button>
    </form>

    {{-- Form Simpan Ton Ikan --}}
    <form method="POST" action="{{ route('presensi.tonikan.store') }}" class="mb-4">
        @csrf
        <input type="hidden" name="kuartal_id" value="{{ $selectedKuartal->id }}">
        
        <div class="mb-3">
            <label for="jumlah_ton">Jumlah Ton Ikan (Kuartal {{ $selectedKuartal->nama_kuartal }})</label>
            <input type="number" name="jumlah_ton" class="form-control" value="{{ old('jumlah_ton', $jumlahTonHariIni) }}" required>
        </div>
        
        <div class="mb-3">
            <label for="harga_ikan_per_ton">Harga Ikan Per Ton (Rp)</label>
            <input type="number" name="harga_ikan_per_ton" class="form-control" value="{{ old('harga_ikan_per_ton', $hargaIkanPerTon) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Data Ton Ikan</button>
    </form>


    {{-- Tabel Presensi --}}
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Aksi Masuk</th>
                <th>Jam Masuk</th>
                <th>Aksi Pulang</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawans as $i => $k)
            @php
                $p = $presensis->get($k->id);
                $totalJam = 0;
                if ($p && $p->jam_masuk && $p->jam_pulang) {
                    $totalJam = \Carbon\Carbon::parse($p->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($p->jam_pulang)) / 60;
                }
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->jenis_kelamin }}</td>
                {{-- Aksi Masuk --}}
                <td>
                    @if(!$p || !$p->jam_masuk)
                        <form method="POST" action="{{ route('presensi.masuk', $k->id) }}">
                            @csrf
                            <input type="hidden" name="kuartal_id" value="{{ $selectedKuartal->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            <button class="btn btn-success btn-sm">✓ Masuk</button>
                        </form>
                    @else
                        <span class="text-success">✓</span>
                    @endif
                </td>

                {{-- Jam Masuk --}}
                <td>{{ $p->jam_masuk ?? '-' }}</td>

                {{-- Aksi Pulang --}}
                <td>
                    @if($p && !$p->jam_pulang)
                        <form method="POST" action="{{ route('presensi.pulang', $k->id) }}">
                            @csrf
                            <input type="hidden" name="kuartal_id" value="{{ $selectedKuartal->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            <button class="btn btn-warning btn-sm">✓ Pulang</button>
                        </form>
                    @elseif($p)
                        <span class="text-warning">✓</span>
                    @else
                        <span>-</span>
                    @endif
                </td>

                {{-- Jam Pulang --}}
                <td>{{ $p->jam_pulang ?? '-' }}</td>

                {{-- Total Jam --}}
                <td>{{ number_format($totalJam, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
