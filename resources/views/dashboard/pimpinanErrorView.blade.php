@extends('layouts.app_pimpinan')

@section('title', 'Dashboard Pimpinan')

@section('content')
<div class="container mt-4">
    <div class="title-box">
        <h3 class="fw-bold m-0">Dashboard</h3>
    </div>

    <div class="dashboard-container" style="height: 100%;">
    <div class="info-row">
        <div class="info-box text-start">
            <div class="fw-bold">Pendapatan</div>
            <div class="text-success fw-bold mt-1">
                Rp {{ number_format(array_sum($pendapatanBulanan), 0, ',', '.') }}
            </div>

        </div>
        
        <div class="info-box">
            <div>Pengeluaran</div>
            <div class="text-danger mt-2">
                Rp {{ number_format(array_sum($pengeluaranBulanan), 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="chart-card bg-yellow-50 p-4 rounded shadow-md mt-4" style="height:auto;">
        <div class="chart-c flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg">
                Grafik Pendapatan <span class="text-dark">vs</span> Pengeluaran
                <span class="text-sm text-gray-600">
                    ({{ $bulanAktif ?? 'Semua Waktu' }})
                </span>
            </h2>
    
            <form method="GET" class="flex items-center gap-2" style="padding-top: 10px">
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="border px-2 py-1 rounded text-sm" required>
                <span class="text-sm">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="border px-2 py-1 rounded text-sm" required>
                <button type="submit" class="bg-blue-500 text-black px-3 py-1 rounded text-sm" style="background: #bdbdf2">
                    Terapkan
                </button>
            </form>
        </div>
    
        <div class="chart-card">
            <canvas id="financeChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
     const originalLabels = {!! json_encode($originalLabels) !!};
    const labelHints = {!! json_encode($extraLabelHints) !!};
    const pendapatanData = {!! json_encode($pendapatanBulanan) !!};
    const pengeluaranData = {!! json_encode($pengeluaranBulanan) !!};

    // Ganti label dengan "..." jika data ekstrem
    const finalLabels = originalLabels.map((label, index) => {
        return labelHints[index] === '...' ? '...' : label;
    });

    const ctx = document.getElementById('financeChart').getContext('2d');
    const financeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: finalLabels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: pendapatanData,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.2)',
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    spanGaps: true
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    spanGaps: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.raw === null) {
                                return 'Data ekstrem disederhanakan';
                            }
                            return `${context.dataset.label}: Rp ${context.raw.toLocaleString('id-ID')}`;
                        }
                    }
                },
                legend: {
                    labels: {
                        usePointStyle: true
                    }
                }
            },
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
