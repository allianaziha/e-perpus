@extends('layouts.backend')

@section('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
    }

    .dashboard-header {
        background: white;
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .dashboard-header h3 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        color: #7f8c8d;
        margin: 0;
        font-size: 0.95rem;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: none;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent-color);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
        color: var(--accent-color);
        display: block;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #7f8c8d;
        font-weight: 500;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .stat-value.small {
        font-size: 1.5rem;
    }

    /* Color Variables */
    .card-purple { --accent-color: #9b59b6; }
    .card-blue { --accent-color: #3498db; }
    .card-green { --accent-color: #2ecc71; }
    .card-pink { --accent-color: #e74c3c; }
    .card-teal { --accent-color: #1abc9c; }
    .card-orange { --accent-color: #f39c12; }

    /* Chart Cards */
    .chart-wrapper {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        height: 100%;
    }

    .chart-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-icon {
        font-size: 1.3rem;
    }

    /* Responsive Grid */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 1.6rem;
        }
        
        .chart-wrapper {
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="dashboard-header">
        <h3>Dashboard Admin</h3>
        <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong> 👋</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card card-purple">
                <i class="bi bi-people-fill stat-icon"></i>
                <div class="stat-label">Total User</div>
                <h3 class="stat-value">{{ number_format($totalUser) }}</h3>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card card-blue">
                <i class="bi bi-book-fill stat-icon"></i>
                <div class="stat-label">Total Buku</div>
                <h3 class="stat-value">{{ number_format($totalBuku) }}</h3>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card card-green">
                <i class="bi bi-grid-3x3-gap-fill stat-icon"></i>
                <div class="stat-label">Total Rak</div>
                <h3 class="stat-value">{{ number_format($totalRak) }}</h3>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card card-pink">
                <i class="bi bi-journal-arrow-down stat-icon"></i>
                <div class="stat-label">Buku Dipinjam</div>
                <h3 class="stat-value">{{ number_format($totalDipinjam) }}</h3>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card card-teal">
                <i class="bi bi-journal-check stat-icon"></i>
                <div class="stat-label">Pengembalian</div>
                <h3 class="stat-value">{{ number_format($totalPengembalian) }}</h3>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card card-orange">
                <i class="bi bi-cash-stack stat-icon"></i>
                <div class="stat-label">Total Denda</div>
                <h3 class="stat-value small">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="chart-wrapper">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <span class="chart-icon">📊</span>
                        Grafik Peminjaman per Minggu
                    </h5>
                </div>
                <canvas id="chartPeminjaman" height="300"></canvas>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="chart-wrapper">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <span class="chart-icon">📈</span>
                        Grafik Denda per Minggu
                    </h5>
                </div>
                <canvas id="chartDenda" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
   // Grafik Peminjaman (Line)
    const ctxPinjam = document.getElementById('chartPeminjaman');
    new Chart(ctxPinjam, {
        type: 'line',
        data: {
            labels: {!! json_encode($mingguLabels) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($peminjamanMingguan) !!},
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Grafik Denda (Bar)
    const ctxDenda = document.getElementById('chartDenda');
    new Chart(ctxDenda, {
        type: 'bar',
        data: {
            labels: {!! json_encode($mingguLabels) !!},
            datasets: [{
                label: 'Jumlah Denda (Rp)',
                data: {!! json_encode($dendaMingguan) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
