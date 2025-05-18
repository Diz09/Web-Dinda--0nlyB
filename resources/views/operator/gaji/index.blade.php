@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Kuartal Gaji</h3>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kuartal</th>
                    <th>Ton Ikan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kuartals as $i => $kuartal)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ route('gaji.kuartal.detail', $kuartal->id) }}">
                                {{ $kuartal->nama_kuartal }}
                            </a>
                        </td>
                        <td>{{ number_format($kuartal->jumlah_ton, 2) }} Ton</td>
                        <td>
                            {{ $kuartal->presensis->min('tanggal') 
                                ? \Carbon\Carbon::parse($kuartal->presensis->min('tanggal'))->format('d-m-Y') 
                                : '-' }}
                        </td>
                        <td>
                            {{ $kuartal->presensis->max('tanggal') 
                                ? \Carbon\Carbon::parse($kuartal->presensis->max('tanggal'))->format('d-m-Y') 
                                : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data kuartal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
