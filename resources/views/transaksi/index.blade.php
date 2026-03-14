@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">📋 Data Transaksi</h4>

        <a href="{{ route('transaksi.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
        </a>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE TRANSAKSI -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light">
            <strong>Daftar Transaksi</strong>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>Konsumen</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $t)
                        <tr>
                            <td>{{ $t->konsumen->nama }}</td>
                            <td>{{ $t->produk->nama }}</td>
                            <td>{{ $t->qty }}</td>
                            <td>Rp {{ number_format($t->harga_satuan,0,',','.') }}</td>
                            <td><strong>Rp {{ number_format($t->total,0,',','.') }}</strong></td>
                            <td>
                                <div class="d-flex gap-2">

                                    <a href="{{ route('transaksi.edit',$t->id) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('transaksi.destroy',$t->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            🗑 Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- PRODUK TERLARIS -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <strong>📦 Produk Terlaris Bulan Ini</strong>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>Produk</th>
                            <th width="200">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produk_terlaris as $p)
                        <tr>
                            <td>{{ $p->produk->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $p->total_qty }} Terjual
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Tidak ada data produk terlaris
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
