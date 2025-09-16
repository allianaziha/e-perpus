@extends('layouts.backend')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h3 class="fw-bold">Dashboard Petugas</h3>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }} 👋</p>
        </div>
    </div>

    {{-- Kotak Statistik --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-dark shadow-sm" style="background-color:#A8D8EA;">
                <div class="card-body text-center">
                    <i class="bi bi-book-half fs-10 mb-2"></i>
                    <h6>Total Buku</h6>
                    <h3>{{ $totalBuku }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-dark shadow-sm" style="background-color:#F9D5E5;">
                <div class="card-body text-center">
                    <i class="bi bi-journal-arrow-down fs-10 mb-2"></i>
                    <h6>Buku Dipinjam</h6>
                    <h3>{{ $totalDipinjam }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-dark shadow-sm" style="background-color:#D9E4DD;">
                <div class="card-body text-center">
                    <i class="bi bi-journal-check fs-10 mb-2"></i>
                    <h6>Pengembalian</h6>
                    <h3>{{ $totalPengembalian }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-dark shadow-sm" style="background-color:#FCE1A8;">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack fs-10 mb-2"></i>
                    <h6>Total Denda</h6>
                    <h5 class="fw-bold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Mingguan --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">📊 Grafik Peminjaman per Minggu</div>
                <div class="card-body">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">📈 Grafik Denda per Minggu</div>
                <div class="card-body">
                    <canvas id="chartDenda"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

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
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
