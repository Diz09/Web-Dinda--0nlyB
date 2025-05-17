@extends('layouts.app_pimpinan')

@section('title', 'Laporan Karyawan')

@section('content')
<div class="container">
    <h1 class="mb-4">Laporan Gaji Karyawan</h1>

    {{-- <form method="GET" action="{{ route('laporan.karyawan') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="filter" class="form-label">Filter Tanggal:</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="minggu_ini" {{ request('filter') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="bulan_ini" {{ request('filter') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="kuartal_terbaru" {{ request('filter') == 'kuartal_terbaru' ? 'selected' : '' }}>Kuartal Terbaru</option>
                    <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>

            <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..." class="form-input"/>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
    </form> --}}

    <form id="filterForm" method="GET" action="{{ route('laporan.karyawan') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="filter" class="form-label">Filter Tanggal:</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="minggu_ini" {{ request('filter') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="bulan_ini" {{ request('filter') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="kuartal_terbaru" {{ request('filter') == 'kuartal_terbaru' ? 'selected' : '' }}>Kuartal Terbaru</option>
                    <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>

            <div class="col-auto">
                <label for="nama" class="form-label">Cari Nama:</label>
                <input type="text" name="nama" id="nama" value="{{ request('nama') }}" class="form-control" placeholder="Ketik nama...">
            </div>

            <div class="col-auto mt-4">
                <a id="downloadExcel" class="btn btn-success">Unduh Excel</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Pekerja</th>
                    <th>Jenis Kelamin</th>
                    <th>No Telepon</th>
                    <th>Total Jam Kerja</th>
                    <th>Gaji per Kuartal</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['karyawan']->nama }}</td>
                        <td>
                            {{ $item['karyawan']->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td>{{ $item['karyawan']->no_telepon }}</td>
                        <td>{{ $item['total_jam_kerja'] }} Jam</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($item['gaji_per_kuartal'] as $gpk)
                                    <li>
                                        Kuartal ID {{ $gpk['kuartal_id'] }}:
                                        Rp {{ number_format($gpk['gaji'], 0, ',', '.') }} 
                                        ({{ $gpk['total_jam'] }} jam)
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <strong>Rp {{ number_format($item['total_gaji'], 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data karyawan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterSelect = document.getElementById('filter');
        const namaInput = document.getElementById('nama');
        const form = document.getElementById('filterForm');
        const downloadBtn = document.getElementById('downloadExcel');

        // Saat filter tanggal diubah
        filterSelect.addEventListener('change', function () {
            form.submit();
        });

        // Saat nama diketik, tunggu 500ms sebelum submit
        let timeout = null;
        namaInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                form.submit();
            }, 500);
        });

        // Unduh Excel
        downloadBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const urlParams = new URLSearchParams(new FormData(form));
            const exportUrl = "{{ route('laporan.karyawan') }}" + '?' + urlParams.toString() + '&export=excel';
            window.location.href = exportUrl;
        });
    });
</script>


@endsection
