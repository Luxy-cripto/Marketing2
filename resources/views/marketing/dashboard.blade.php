@extends('layouts.admin2')

@section('content')

    <div class="container-fluid">

        <h3 class="fw-bold mb-3">📊 Dashboard Marketing</h3>
        <p class="text-muted mb-4">
            Statistik Performa Bulan {{ now()->translatedFormat('F Y') }}
        </p>

        <div class="row g-3">

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-bullseye fa-2x text-secondary mb-2"></i>
                        <p class="text-muted mb-1">Target Lead</p>
                        <h2 class="fw-bold">{{ $targetLead }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                        <p class="text-muted mb-1">Lead Masuk</p>
                        <h2 class="fw-bold text-primary">{{ $totalLead }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                        <p class="text-muted mb-1">Deal</p>
                        <h2 class="fw-bold text-success">{{ $deal }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-user-times fa-2x text-danger mb-2"></i>
                        <p class="text-muted mb-1">Tidak Tertarik</p>
                        <h2 class="fw-bold text-danger">{{ $tidakTertarik }}</h2>
                    </div>
                </div>
            </div>

        </div>


        <div class="row g-3 mt-2">

            <div class="col-md-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="text-muted mb-1">Closing</p>
                        <h2 class="fw-bold text-success">{{ $closing }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-money-bill-wave fa-2x text-primary mb-2"></i>
                        <p class="text-muted mb-1">Total Omset</p>
                        <h2 class="fw-bold text-primary">
                            Rp {{ number_format($totalOmset, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-2">

            <!-- LUNAS -->
            <div class="col-md-6">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>

                        <p class="text-muted mb-1">Lunas</p>

                        <h2 class="fw-bold text-success">
                            {{ $jumlahLunas }} Transaksi
                        </h2>

                        <small class="text-muted">
                            Rp {{ number_format($totalLunas, 0, ',', '.') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- BELUM DIBAYAR -->
            <div class="col-md-6">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <i class="fas fa-money-bill-wave fa-2x text-primary mb-2"></i>

                        <p class="text-muted mb-1">Belum Dibayar</p>

                        <h2 class="fw-bold text-primary">
                            {{ $jumlahBelumBayar }} Transaksi
                        </h2>

                        <small class="text-muted">
                            Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}
                        </small>
                    </div>
                </div>
            </div>

        </div>


        <div class="row mt-3">

            <div class="col-md-12">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h5 class="fw-bold">📋 Keterangan</h5>

                        <ul>
                            <li><strong>Target Lead:</strong> Jumlah lead yang ditargetkan untuk bulan ini.</li>
                            <li><strong>Lead Masuk:</strong> Jumlah lead yang berhasil masuk ke sistem.</li>
                            <li><strong>Deal:</strong> Jumlah lead yang berhasil dikonversi menjadi deal.</li>
                            <li><strong>Tidak Tertarik:</strong> Jumlah lead yang menyatakan tidak tertarik.</li>
                            <li><strong>Closing:</strong> Jumlah deal yang berhasil ditutup dengan sukses.</li>
                            <li><strong>Total Omset:</strong> Total pendapatan yang dihasilkan dari deal yang berhasil
                                ditutup.</li>
                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-3">

            <!-- DEAL SUDAH BAYAR -->
            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-header">
                        <h5 class="fw-bold text-success">
                            <i class="fas fa-money-check-alt"></i> Deal Sudah Bayar
                        </h5>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>No HP</th>
                                    <th>Produk</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($sudahBayar as $k)

                                    <tr>
                                        <td>{{ $k->nama }}</td>
                                        <td>{{ $k->no_hp }}</td>

                                        <td>
                                            @php
                                                $transaksi = $k->transaksis->first();
                                            @endphp

                                            @if($transaksi && $transaksi->details->count())
                                                <ul class="mb-0 ps-3" style="font-size: 13px;">
                                                    @foreach($transaksi->details as $d)
                                                        <li>{{ $d->produk->nama ?? '-' }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="text-success fw-bold">
                                            Rp {{ number_format($k->transaksis->sum('total'), 0, ',', '.') }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Belum ada pembayaran
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>



            <!-- DEAL BELUM BAYAR -->
            <div class="col-md-6">
                <div class="card shadow-sm">

                    <div class="card-header">
                        <h5 class="fw-bold text-danger">
                            <i class="fas fa-clock"></i> Deal Belum Bayar
                        </h5>
                    </div>

                    <div class="card-body p-0">

                        <!-- WAJIB: biar tidak tembus -->
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-bordered">

                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%">Nama</th>
                                        <th style="width: 20%">No HP</th>
                                        <th style="width: 30%">Produk</th>
                                        <th style="width: 25%">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($belumBayar as $k)
                                        <tr>
                                            <td class="wrap-text">{{ $k->nama }}</td>
                                            <td class="wrap-text">{{ $k->no_hp }}</td>

                                            <td class="wrap-text">
                                                @php
                                                    $transaksi = $k->transaksis->first();
                                                @endphp

                                                @if($transaksi && $transaksi->details->count())
                                                    <ul class="mb-0 ps-3" style="font-size: 13px;">
                                                        @foreach($transaksi->details as $d)
                                                            <li>{{ $d->produk->nama ?? '-' }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    Menunggu Pembayaran
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col-md-12">

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="fw-bold">📦 Target vs Omset Per Produk</h5>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Target Lead</th>
                                        <th>Target Omset</th>
                                        <th>Omset Bulan Ini</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($targetDetails as $d)

                                        @php
                                            $omset = $omsetPerProduk[$d->produk_id] ?? 0;

                                            $progressProduk = $d->target_omset_produk > 0
                                                ? min(($omset / $d->target_omset_produk) * 100, 100)
                                                : 0;
                                        @endphp

                                        <tr>
                                            <td style="max-width:250px;">
                                                {{ $d->produk->nama ?? '-' }}
                                            </td>
                                            <td>{{ $d->target_qty }}</td>

                                            <td>
                                                Rp {{ number_format($d->target_omset_produk, 0, ',', '.') }}
                                            </td>

                                            <td class="text-success fw-bold">
                                                Rp {{ number_format($omset, 0, ',', '.') }}
                                            </td>

                                            <td style="width:200px">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: {{ $progressProduk }}%">
                                                        {{ round($progressProduk) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                Belum ada target produk
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="row mt-3">

    <div class="col-md-12">

        <div class="card shadow-sm">

            <div class="card-body text-center">

                <h5 class="fw-bold mb-3">💰 Target Omset</h5>

                <h2 class="fw-bold text-success">
                    Rp {{ number_format($targetOmset, 0, ',', '.') }}
                </h2>

                <p class="text-muted mb-0">
                    Total omset dari seluruh produk bulan ini
                </p>

            </div>

        </div>

    </div>

</div>



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


            <div class="col-md-4">

                <div class="card">

                    <div class="badge bg-white shadow-sm text-dark p-2 px-3 rounded-pill border">
                        <i class="fas fa-calendar-alt text-primary me-2"></i> {{ date('l, d M Y') }}
                        jam:{{ date('H:i') }}
                    </div>

                    <div class="card-header">
                        <h5 class="fw-bold">📞 Follow Up Hari Ini</h5>
                    </div>

                    <div class="card-body p-0">

                        <div style="max-height:300px;overflow-y:auto">

                            @forelse($followups as $f)

                                <div class="d-flex align-items-center border-bottom p-3">

                                    <div class="me-3">

                                        <span class="badge bg-warning">
                                            <i class="fas fa-phone"></i>
                                        </span>

                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="fw-bold">
                                            {{ $f->konsumen->nama ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $f->konsumen->no_hp ?? '-' }}
                                        </small>

                                    </div>

                                    <span class="badge bg-warning">
                                        {{ $f->status }}
                                    </span>

                                </div>

                            @empty

                                <div class="text-center p-4 text-muted">
                                    Tidak ada follow up hari ini
                                </div>

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

                labels: {!! json_encode($kpi->map(fn($i) => $i->user ? $i->user->name : 'Unknown')) !!},

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
