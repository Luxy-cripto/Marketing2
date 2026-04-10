@extends('layouts.admin2')

@section('content')
<div class="container-fluid py-4" style="background-color: #f0f2f5; min-height: 100vh;">

    {{-- HEADER SECTION --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h3 mb-1 text-gray-800 fw-bold">Dashboard Analytics</h2>
            <p class="text-muted small mb-0">Pantau performa penjualan dan aktivitas tim secara real-time.</p>
        </div>
        <div class="text-end">
            <div class="badge bg-white shadow-sm text-dark p-2 px-3 rounded-pill border">
                <i class="fas fa-calendar-alt text-primary me-2"></i> {{ date('l, d M Y') }}
            </div>
        </div>
    </div>

    {{-- ALERT SECTION --}}
    <div class="row">
        <div class="col-12">
            @if(count($targetNotifications) > 0)
                <div class="alert alert-custom bg-white border-0 border-start border-4 border-success shadow-sm mb-4 fade show">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-light-success text-success rounded-circle me-3">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="text-dark">Target Omset Tercapai!</strong>
                            <div class="text-muted small">
                                @foreach($targetNotifications as $notif)
                                    <span class="me-2">• <strong>{{ $notif['user_name'] }}</strong> (Rp {{ number_format($notif['total_omset'], 0, ',', '.') }})</span>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($todayFollowUps->count() > 0)
                <div class="alert alert-custom bg-white border-0 border-start border-4 border-warning shadow-sm mb-4 fade show">
                    <div class="d-flex align-items-center text-dark">
                        <div class="icon-shape bg-light-warning text-warning rounded-circle me-3">
                            <i class="fas fa-bell animate-bell"></i>
                        </div>
                        <div class="flex-grow-1">
                            <span>Anda memiliki <strong>{{ $todayFollowUps->count() }}</strong> jadwal follow-up yang harus diselesaikan hari ini.</span>
                        </div>
                        <a href="#" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold shadow-sm">Selesaikan</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row mb-4 g-3">
        @php
            $stats = [
                ['label' => 'Total Konsumen', 'value' => $totalKonsumen, 'icon' => 'fa-users', 'bg' => 'bg-gradient-primary'],
                ['label' => 'Total Prospek', 'value' => $totalProspek, 'icon' => 'fa-user-clock', 'bg' => 'bg-gradient-warning'],
                ['label' => 'Total Deal', 'value' => $totalDeal, 'icon' => 'fa-handshake', 'bg' => 'bg-gradient-success'],
                ['label' => 'Total Omset', 'value' => 'Rp ' . number_format($totalOmset, 0, ',', '.'), 'icon' => 'fa-wallet', 'bg' => 'bg-gradient-info'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm card-hover h-100 overflow-hidden">
                <div class="card-body p-4 {{ $stat['bg'] }} position-relative">
                    <div class="position-relative" style="z-index: 1;">
                        <p class="text-uppercase small fw-bold mb-1 {{ str_contains($stat['bg'], 'warning') ? 'text-dark opacity-75' : 'text-white-50' }}">
                            {{ $stat['label'] }}
                        </p>
                        <h3 class="fw-bold mb-0 {{ str_contains($stat['bg'], 'warning') ? 'text-dark' : 'text-white' }}">
                            {{ is_numeric($stat['value']) ? number_format($stat['value'], 0, ',', '.') : $stat['value'] }}
                        </h3>
                    </div>
                    <i class="fas {{ $stat['icon'] }} position-absolute end-0 bottom-0 mb-n2 me-n2 opacity-25" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- GRAFIK TREN (LINE CHART) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom-0">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-chart-line text-primary me-2"></i>Tren Performa Deal ({{ date('Y') }})</h6>
                    <button class="btn btn-sm btn-light border px-3 rounded-pill shadow-sm">Tahun Ini</button>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="dealChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOLLOW UP TERBARU (TABLE) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-history text-primary me-2"></i>Follow-Up Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 320px;">
                        <table class="table table-hover align-middle mb-0">
                            <tbody class="border-top-0">
                                @forelse($followUps->take(6) as $followUp)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark mb-0 text-truncate" style="max-width: 140px;">{{ $followUp->konsumen->nama ?? '-' }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 140px;">{{ $followUp->catatan }}</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge rounded-pill px-3 py-2 {{ $followUp->status == 'Deal' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                            {{ $followUp->status ?? 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-5 text-muted small">Tidak ada aktivitas terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="#" class="small fw-bold text-primary text-decoration-none">Lihat Semua Aktivitas <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        {{-- TOP PRODUK (HORIZONTAL BAR CHART) --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-box-open text-primary me-2"></i>Top 5 Produk Terlaris (Qty)</h6>
                </div>
                <div class="card-body">
                    <div style="height: 280px;">
                        <canvas id="produkChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS CUSTOM --}}
<style>
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }

    .icon-shape { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .bg-light-success { background-color: rgba(28, 200, 138, 0.15); }
    .bg-light-warning { background-color: rgba(246, 194, 62, 0.15); }

    .card { border-radius: 15px; }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.1) !important; }

    .bg-soft-success { background-color: #e8fadf; color: #1cc88a; }
    .bg-soft-warning { background-color: #fff2d6; color: #f6c23e; }

    .animate-bell { animation: bell 2s infinite; }
    @keyframes bell {
        0%, 100% { transform: rotate(0); }
        15% { transform: rotate(15deg); }
        30% { transform: rotate(-15deg); }
        45% { transform: rotate(10deg); }
        60% { transform: rotate(-10deg); }
    }

    .table-responsive::-webkit-scrollbar { width: 5px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
</style>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Line Chart: Tren Deal
    const ctxDeal = document.getElementById('dealChart').getContext('2d');
    const dealGradient = ctxDeal.createLinearGradient(0, 0, 0, 400);
    dealGradient.addColorStop(0, 'rgba(78, 115, 223, 0.2)');
    dealGradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

    new Chart(ctxDeal, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Total Deal',
                data: @json($dealPerBulan),
                borderColor: '#4e73df',
                borderWidth: 3,
                backgroundColor: dealGradient,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4e73df',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f8f9fc' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    // 2. Horizontal Bar Chart: Top Produk
    const ctxProduk = document.getElementById('produkChart').getContext('2d');
    new Chart(ctxProduk, {
        type: 'bar',
        data: {
            labels: @json($labelsProduk),
            datasets: [{
                label: 'Unit Terjual',
                data: @json($dataQtyProduk),
                backgroundColor: '#1cc88a',
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => ` Terjual: ${ctx.raw} unit` } }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#f8f9fc' },
                    ticks: { stepSize: 1 }
                },
                y: {
                    grid: { display: false },
                    ticks: {
                        font: { weight: 'bold' },
                        color: '#4e73df'
                    }
                }
            }
        }
    });
</script>
@endsection
