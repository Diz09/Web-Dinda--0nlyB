@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- <script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 5000,
            timerProgressBar: true,
            confirmButtonText: 'Oke',
        });
    @elseif (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session("error") }}',
            timer: 5000,
            timerProgressBar: true,
            confirmButtonText: 'Oke',
        });
    @endif
</script> --}}



@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Presensi Harian Pekerja</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Pilih Kuartal --}}
    <form id="formKuartal" class="mb-3">
        <label for="kuartal_id">Pilih Kuartal</label>
        <div class="input-group i-g">
            <select name="kuartal_id" id="kuartal_id" class="form-control">
                @foreach($kuartals as $k)
                    <option value="{{ $k->id }}" {{ $selectedKuartal->id == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kuartal }}
                    </option>
                @endforeach
            </select>
            <!-- Tombol untuk membuka Modal -->
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editKuartalModal">Edit Kuartal</button>
            <button type="button" class="btn btn-outline-primary" id="btnLihatKuartal">Lihat</button>
            @include('operator.presensi.edit')
        </div>
    </form>

    {{-- checkbox fitur otomatis --}}
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="modeOtomatis">
        <label class="form-check-label" for="modeOtomatis">Mode Otomatis (Gunakan Waktu Saat Ini)</label>
    </div>

    {{-- Tabel Presensi --}}
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                {{-- <th>Jenis Kelamin</th> --}}
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
                {{-- Aksi Masuk --}}
                <td>
                    @if(!$p || !$p->jam_masuk)
                        <form method="POST" action="{{ route('presensi.masuk', $k->id) }}" class="formPresensi">
                            @csrf
                            <input type="hidden" name="kuartal_id" value="{{ $selectedKuartal->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            <input type="hidden" name="jam" class="inputJam">
                            <input type="time" class="form-control inputManualJam d-none" name="jam_manual">
                            <button class="btn btn-success btn-sm btnSubmitMasuk mt-1">✓ Masuk</button>
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
                        <form method="POST" action="{{ route('presensi.pulang', $k->id) }}" class="formPresensi">
                            @csrf
                            <input type="hidden" name="kuartal_id" value="{{ $selectedKuartal->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            <input type="hidden" name="jam" class="inputJam">
                            <input type="time" class="form-control inputManualJam d-none" name="jam_manual">
                            <button class="btn btn-warning btn-sm btnSubmitPulang mt-1">✓ Pulang</button>
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

<!-- Tambahkan ini di dalam <head> atau sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/presensi.js') }}"></script>
@endsection
