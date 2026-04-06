@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-4">📊 Reports Dashboard</h3>

    <!-- FILTER -->
    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <input type="date" name="start_date"
                value="{{ \Carbon\Carbon::parse($start)->format('Y-m-d') }}"
                class="form-control">
        </div>

        <div class="col-md-3">
            <input type="date" name="end_date"
                value="{{ \Carbon\Carbon::parse($end)->format('Y-m-d') }}"
                class="form-control">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- CARD -->
    <div class="row">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Transaksi</h6>
                    <h3>{{ $totalTransaksi }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Omzet</h6>
                    <h3>Rp {{ number_format($totalOmzet,0,',','.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Produk Terlaris</h6>
                    <h5>
                        {{ $produkTerlaris->first()->nama ?? '-' }}
                    </h5>
                </div>
            </div>
        </div>

    </div>

    <!-- PRODUK TERLARIS -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            🔥 Top Produk
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($produkTerlaris as $p)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $p->nama }}
                    <span class="badge bg-primary">{{ $p->total }}</span>
                </li>
                @empty
                <li class="list-group-item text-center text-muted">
                    Tidak ada data
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- TRANSAKSI -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            📋 Transaksi Terbaru
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered">

                <thead>
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
                        <td>{{ $t->created_at->format('d-m-Y') }}</td>
                        <td>{{ $t->konsumen->nama ?? '-' }}</td>
                        <td>{{ $t->produk->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($t->total,0,',','.') }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ $t->status }}
                            </span>
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
