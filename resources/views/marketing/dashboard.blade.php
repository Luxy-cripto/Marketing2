@extends('layouts.admin2')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">📊 KPI Bulan {{ now()->format('F Y') }}</h4>

    <div class="row mb-4 g-3">

        <!-- Lead Masuk -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <div class="card-body">
                    <i class="fas fa-user-plus fa-2x text-info mb-2"></i>
                    <h6 class="text-muted">Lead Masuk</h6>
                    <h2 class="fw-bold text-info">{{ $leadMasuk }}</h2>
                </div>
            </div>
        </div>

        <!-- Closing -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <div class="card-body">
                    <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                    <h6 class="text-muted">Closing Bulan Ini</h6>
                    <h2 class="fw-bold text-success">{{ $closing }}</h2>
                </div>
            </div>
        </div>

        <!-- Target Bulanan -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                    <h6 class="text-muted">Target Bulanan</h6>
                    <h5>{{ $target ? 'Rp ' . number_format($target->target_omset,0,',','.') : 'Belum Diset' }}</h5>
                    <div class="progress mb-2" style="height:25px;">
                        <div class="progress-bar bg-gradient" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ round($progress) }}%
                        </div>
                    </div>
                    <small class="text-muted">Total Omset: Rp {{ number_format($totalOmset,0,',','.') }}</small>
                </div>
            </div>
        </div>

    </div>

    <!-- KPI Tim Marketing -->
    <div class="card mb-4 shadow-sm border-0 p-3">
        <div class="card-body">
            <h5>📈 KPI Tim Marketing (Lead per Marketing)</h5>
            <canvas id="kpiChart" height="100"></canvas>
        </div>
    </div>

    <!-- Follow-Up Hari Ini -->
    <div class="card mb-4 shadow-sm border-0 p-3">
        <div class="card-body">
            <h5>📅 Reminder Follow-Up Hari Ini</h5>
            <div id="followUpList" class="mb-2 p-2" style="max-height:250px; overflow-y:auto; border:1px solid #eee; border-radius:5px;">
                @if($followups->count())
                    <ul class="list-unstyled mb-0">
                        @foreach($followups as $f)
                            <li class="py-1 border-bottom">
                                <strong>{{ $f->konsumen ? $f->konsumen->nama : '-' }}</strong>
                                - {{ $f->konsumen ? $f->konsumen->no_hp : '-' }}
                                <span class="badge bg-warning text-dark ms-2">{{ $f->status }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Tidak ada follow-up hari ini</p>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Navbar Notification Live --}}
<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
    <li class="nav-item topbar-icon dropdown hidden-caret">
        <a
            class="nav-link dropdown-toggle"
            href="#"
            id="notifDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <i class="fa fa-bell"></i>
            <span id="followup-count" class="notification">{{ $followupPendingCount }}</span>
        </a>

        <ul
            class="dropdown-menu notif-box animated fadeIn"
            aria-labelledby="notifDropdown"
        >
            <li>
                <div class="dropdown-title">
                    Kamu punya <span id="followup-count-text">{{ $followupPendingCount }}</span> follow-up hari ini
                </div>
            </li>
            <li>
                <div class="notif-scroll scrollbar-outer">
                    <div id="followup-list" class="notif-center">
                        @foreach($followups as $f)
                            <a href="{{ route('followups.index') }}">
                                <div class="notif-icon notif-warning"><i class="fas fa-phone"></i></div>
                                <div class="notif-content">
                                    <span class="block">{{ $f->konsumen ? $f->konsumen->nama : '-' }}</span>
                                    <span class="time">{{ \Carbon\Carbon::parse($f->follow_up_date)->format('H:i') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
            <li>
                <a class="see-all" href="{{ route('followups.index') }}">
                    Lihat semua follow-up <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </li>
</ul>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const kpiCtx = document.getElementById('kpiChart').getContext('2d');
new Chart(kpiCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($kpi->map(fn($item) => $item->user ? $item->user->name : 'Unknown')) !!},
        datasets: [{
            label: 'Lead Bulan Ini',
            data: {!! json_encode($kpi->pluck('total')) !!},
            backgroundColor: 'rgba(0, 123, 255, 0.6)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive:true,
        plugins: { legend:{display:false} },
        scales: { y: { beginAtZero:true } }
    }
});

// --- Live follow-up update ---
async function fetchFollowupsToday() {
    try {
        const response = await fetch('{{ route("marketing.followups.today") }}');
        const data = await response.json();

        // Update count badge
        document.getElementById('followup-count').textContent = data.length;
        document.getElementById('followup-count-text').textContent = data.length;

        // Update dropdown list
        const listContainer = document.getElementById('followup-list');
        if (!listContainer) return;

        listContainer.innerHTML = '';
        if (data.length === 0) {
            listContainer.innerHTML = '<p class="text-muted px-3">Tidak ada follow-up hari ini</p>';
        } else {
            data.forEach(f => {
                const a = document.createElement('a');
                a.href = '{{ route("followups.index") }}';
                a.classList.add('notif-item');
                a.innerHTML = `
                    <div class="notif-icon notif-warning"><i class="fas fa-phone"></i></div>
                    <div class="notif-content">
                        <span class="block">${f.konsumen ? f.konsumen.nama : '-'}</span>
                        <span class="time">${new Date(f.follow_up_date).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</span>
                    </div>`;
                listContainer.appendChild(a);
            });
        }
    } catch (err) {
        console.error('Error fetch follow-ups:', err);
    }
}

fetchFollowupsToday();
setInterval(fetchFollowupsToday, 30000);
</script>
@endsection
