@extends('layouts.admin2')

@section('content')
<div class="container-fluid">

    <h2 class="mb-4">Dashboard Admin</h2>

    {{-- NOTIF TARGET --}}
    @if(count($targetNotifications) > 0)
    <div class="alert alert-success">
        🎉 <strong>Target Omset Tercapai:</strong>
        <ul class="mb-0">
            @foreach($targetNotifications as $notif)
            <li>
                {{ $notif['user_name'] }}:
                Rp {{ number_format($notif['total_omset'],0,',','.') }}
                / Target Rp {{ number_format($notif['target_omset'],0,',','.') }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- FOLLOW UP HARI INI --}}
    @php
        $todayFollowUps = $followUps->where('follow_up_date', '>=', now()->startOfDay());
    @endphp
    @if($todayFollowUps->count() > 0)
    <div class="alert alert-warning">
        🔔 Ada <strong>{{ $todayFollowUps->count() }}</strong> follow-up hari ini!
    </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-primary p-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users fa-2x me-3"></i>
                    <div>
                        <h6>Total Konsumen</h6>
                        <h3>{{ $totalKonsumen }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-warning p-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-check fa-2x me-3"></i>
                    <div>
                        <h6>Total Prospek</h6>
                        <h3>{{ $totalProspek }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-success p-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-handshake fa-2x me-3"></i>
                    <div>
                        <h6>Total Deal</h6>
                        <h3>{{ $totalDeal }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white bg-info p-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-money-bill-wave fa-2x me-3"></i>
                    <div>
                        <h6>Total Omset</h6>
                        <h4>Rp {{ number_format($totalOmset,0,',','.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK DEAL --}}
    <div class="card mb-4 shadow-sm p-3">
        <h5>Grafik Deal Tahun {{ date('Y') }}</h5>
        <canvas id="dealChart" height="100"></canvas>
    </div>

    {{-- TABEL FOLLOW UP --}}
    <div class="card shadow-sm p-3">
        <h5>10 Follow-Up Terbaru</h5>
        <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>#</th>
                        <th>Konsumen</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Tanggal Follow-Up</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($followUps as $index => $followUp)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $followUp->konsumen->nama ?? '-' }}</td>
                        <td>
                            <span class="badge
                                {{ $followUp->status=='Deal'?'bg-success':($followUp->status=='Follow-Up'?'bg-warning':'bg-secondary') }}">
                                {{ $followUp->status ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $followUp->catatan ?? '-' }}</td>
                        <td>
                            {{ $followUp->follow_up_date
                                ? \Carbon\Carbon::parse($followUp->follow_up_date)->format('d M Y H:i')
                                : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada follow-up</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- CHART SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('dealChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Total Deal',
            data: @json($dealPerBulan),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
