@extends('layouts.app_pimpinan')

@section('title', 'Dashboard Pimpinan')

@section('content')
<div class="container mt-4">
    <div class="title-box">
        <h3 class="fw-bold m-0">Dashboard</h3>
    </div>

    <div class="dashboard-container" style="height: 100%;"y>
    <div class="info-row">
        <div class="info-box text-start">
            <div class="fw-bold">Pendapatan</div>
            <div class="text-success fw-bold mt-1">Rp {{ number_format($keuangan['pendapatan'], 0, ',', '.') }}</div>
        </div>
        
        <div class="info-box">
            <div>Pengeluaran</div>
            <div class="text-danger mt-2">
                Rp {{ number_format($keuangan['pengeluaran'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="chart-card bg-yellow-50 p-4 rounded shadow-md mt-4" style="height:auto;">
        <div class="chart-c flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg">
                Grafik Pendapatan <span class="text-dark">vs</span> Pengeluaran
                <span class="text-sm text-gray-600">({{ $bulanAktif }})</span>
            </h2>
    
            <form method="GET" class="flex items-center gap-2" style="padding-top: 10px">
                <select name="filter" class="border px-2 py-1 rounded text-sm">
                    <option value="tahun" {{ request('filter') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                    <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                </select>
                <input type="number" name="year" placeholder="Tahun" value="{{ request('year', now()->year) }}"
                    class="filter-info border px-2 py-1 rounded w-20 text-sm">
                <input type="number" name="month" placeholder="Bulan" value="{{ request('month', now()->month) }}"
                    class="filter-info border px-2 py-1 rounded w-16 text-sm">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Terapkan</button>
            </form>
        </div>
    
        <div class="chart-card">
            <canvas id="financeChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    const financeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Pendapatan',
                    data: {!! json_encode($pendapatanBulanan) !!},
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    fill: false,
                    tension: 0.4
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode($pengeluaranBulanan) !!},
                    borderColor: 'red',
                    backgroundColor: 'red',
                    fill: false,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
