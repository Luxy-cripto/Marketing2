@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <h3 class="fw-bold mb-4">📊 Reports Dashboard</h3>

    <!-- FILTER -->
    <form method="GET" class="row mb-4">

        <div class="col-md-3">
            <input type="date"
                   name="start_date"
                   value="{{ \Carbon\Carbon::parse($start)->format('Y-m-d') }}"
                   class="form-control">
        </div>

        <div class="col-md-3">
            <input type="date"
                   name="end_date"
                   value="{{ \Carbon\Carbon::parse($end)->format('Y-m-d') }}"
                   class="form-control">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Filter
            </button>
        </div>

    </form>

    <!-- CARD SUMMARY -->
    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h2>{{ $totalTransaksi }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Omzet</h6>
                    <h2>
                        Rp {{ number_format($totalOmzet,0,',','.') }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Produk Terlaris</h6>

                    <h4>
                        {{ $produkTerlaris->first()->nama ?? '-' }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    <!-- TOP PRODUK -->
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header">
            🔥 Top Produk Terlaris
        </div>

        <div class="card-body">

            <ul class="list-group">

                @forelse($produkTerlaris as $p)

                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        {{ $p->nama }}

                        <span class="badge bg-primary">
                            {{ $p->total }}
                        </span>

                    </li>

                @empty

                    <li class="list-group-item text-center text-muted">
                        Tidak ada data
                    </li>

                @endforelse

            </ul>

        </div>

    </div>

    <!-- TRANSAKSI TERBARU -->
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header">
            📋 Transaksi Terbaru
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Konsumen</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transaksi as $t)

                        <tr>

                            <td>
                                {{ $t->created_at->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ $t->konsumen->nama ?? '-' }}
                            </td>

                            <td>

                                @forelse($t->produks as $produk)

                                    <span class="badge bg-primary me-1 mb-1">
                                        {{ $produk->nama }}
                                    </span>

                                @empty

                                    -

                                @endforelse

                            </td>

                            <td>
                                Rp {{ number_format($t->total,0,',','.') }}
                            </td>

                            <td>

                                @if(strtolower($t->status) == 'lunas')

                                    <span class="badge bg-success">
                                        {{ $t->status }}
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        {{ $t->status }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada transaksi
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
