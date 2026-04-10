@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h4 class="card-title mb-0">📄 Detail Transaksi</h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3"><b>Konsumen</b></div>
                <div class="col-md-9">: {{ $transaksi->konsumen->nama ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3"><b>Produk</b></div>
                <div class="col-md-9">
                    :
                    @foreach($transaksi->details as $d)
                        {{ $d->produk->nama ?? '-' }} ({{ $d->qty }} pcs)<br>
                    @endforeach
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3"><b>Total</b></div>
                <div class="col-md-9">
                    : <strong class="text-success">
                        Rp {{ number_format($transaksi->total,0,',','.') }}
                      </strong>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3"><b>Tanggal</b></div>
                <div class="col-md-9">
                    : {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">

                <a href="{{ route('transaksi.invoice',$transaksi->id) }}"
                   class="btn btn-primary">
                    📄 Download Invoice
                </a>

                <a href="https://wa.me/{{ $transaksi->konsumen->no_hp ?? '' }}?text=Halo%20{{ $transaksi->konsumen->nama ?? '' }}%20ini%20invoice%20transaksi%20anda%20#{{ $transaksi->id }}"
                   class="btn btn-success"
                   target="_blank">
                    📲 Kirim WhatsApp
                </a>

                <a href="{{ route('transaksi.index') }}"
                   class="btn btn-secondary">
                    ← Kembali
                </a>

            </div>

        </div>

    </div>

</div>

@endsection
