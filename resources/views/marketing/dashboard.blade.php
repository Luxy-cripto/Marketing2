@extends('layouts.admin2')

@section('content')
<div class="container-fluid">

    <h3 class="fw-bold mb-3">📊 Dashboard Marketing</h3>
    <p class="text-muted mb-4">Statistik Performa Bulan {{ now()->translatedFormat('F Y') }}</p>

    <div class="row g-3">
        @foreach ([
            ['Target Lead', $targetLead, 'fas fa-bullseye text-secondary'],
            ['Lead Masuk', $totalLead, 'fas fa-user-plus text-primary'],
            ['Deal', $deal, 'fas fa-handshake text-success'],
            ['Tidak Tertarik', $tidakTertarik, 'fas fa-user-times text-danger']
        ] as $card)
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="{{ $card[2] }} fa-2x mb-2"></i>
                    <p class="text-muted mb-1">{{ $card[0] }}</p>
                    <h2 class="fw-bold">{{ $card[1] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3 mt-2">
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="text-muted mb-1">Closing</p>
                    <h2 class="fw-bold text-success">{{ $closing ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave fa-2x text-primary mb-2"></i>
                    <p class="text-muted mb-1">Total Omset</p>
                    <h2 class="fw-bold text-primary">Rp {{ number_format($totalOmset ?? 0, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Sudah Bayar --}}
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold text-success"><i class="fas fa-money-check-alt"></i> Deal Sudah Bayar</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sudahBayar as $k)
                            <tr>
                                <td>{{ $k->nama }}</td>
                                <td>{{ $k->no_hp }}</td>
                                <td class="text-success fw-bold">{{ $k->transaksis?->sum('total') ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada pembayaran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabel Belum Bayar --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold text-danger"><i class="fas fa-clock"></i> Deal Belum Bayar</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($belumBayar as $k)
                            <tr>
                                <td>{{ $k->nama }}</td>
                                <td>{{ $k->no_hp }}</td>
                                <td><span class="badge bg-warning">Menunggu Pembayaran</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Target Omset --}}
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">🎯 Target Omset</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Progress {{ round($progress) }}%</span>
                        <span class="fw-bold text-primary">{{ $target ? 'Rp '.number_format($target->target_omset,0,',','.') : 'Belum Diset' }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Chart --}}
    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold">📈 Lead per Marketing</h5>
                </div>
                <div class="card-body">
                    <div style="height:300px">
                        <canvas id="kpiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Followup --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold">📅 Follow-Up Hari Ini</h5>
                </div>
                <div class="card-body p-0">
                    <div style="max-height:300px;overflow-y:auto">
                        @forelse($followups as $f)
                        <div class="d-flex align-items-center border-bottom p-3">
                            <div class="me-3"><span class="badge bg-warning"><i class="fas fa-phone"></i></span></div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $f->konsumen->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $f->konsumen->no_hp ?? '-' }}</small>
                            </div>
                            <span class="badge bg-warning">{{ $f->status ?? '-' }}</span>
                        </div>
                        @empty
                        <div class="text-center p-4 text-muted">Tidak ada follow up hari ini</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('kpiChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($kpi->map(fn($i) => $i->user->name ?? 'Unknown')) !!},
        datasets: [{
            data: {!! json_encode($kpi->pluck('total')) !!},
            backgroundColor: 'rgba(23,125,255,0.7)',
            borderRadius: 10
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

@endsection
