@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Kloter Gaji</h3>

    <div>Filter tahun</div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kloter</th>
                    <th>Ton Ikan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kloters as $i => $kloter)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ route('gaji.kloter.detail', $kloter->id) }}">
                                {{ $kloter->nama_kloter }}
                            </a>
                        </td>
                        <td>{{ number_format($kloter->jumlah_ton, 2) }} Kg</td>
                        <td>
                            {{ $kloter->presensis->min('tanggal') 
                                ? \Carbon\Carbon::parse($kloter->presensis->min('tanggal'))->format('d-m-Y') 
                                : '-' }}
                        </td>
                        <td>
                            {{ $kloter->presensis->max('tanggal') 
                                ? \Carbon\Carbon::parse($kloter->presensis->max('tanggal'))->format('d-m-Y') 
                                : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data Kloter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
