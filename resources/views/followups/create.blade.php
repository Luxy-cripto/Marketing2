@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0">

        <!-- Header -->
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-semibold">
                ➕ Tambah Follow-Up
            </h4>
        </div>

        <div class="card-body">

            <!-- Error -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('followups.store') }}" method="POST">
                @csrf

                <!-- Konsumen -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Konsumen</label>
                    <select name="konsumen_id" class="form-select" required>
                        <option value="">-- Pilih Konsumen --</option>

                        @foreach($konsumens as $k)
                            <option value="{{ $k->id }}">
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 🔥 TRANSAKSI -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Transaksi</label>
                    <select name="transaksi_id" id="transaksi_select" class="form-select" required>
                        <option value="">-- Pilih Transaksi --</option>

                        @foreach($transaksis as $trx)
                            <option value="{{ $trx->id }}"
                                data-total="{{ $trx->total }}"
                                data-produk='@json(
                                    $trx->details->map(function($d){
                                        return [
                                            "nama" => $d->produk->nama,
                                            "qty" => $d->qty
                                        ];
                                    })
                                )'>

                                INV-{{ $trx->id }} |
                                {{ $trx->konsumen->nama ?? '-' }} |
                                {{ $trx->details->count() }} barang |
                                Rp {{ number_format($trx->total) }}

                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 🔥 PRODUK LIST -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Produk Dibeli</label>
                    <div id="produk_list" class="form-control bg-light" style="min-height:80px"></div>
                </div>

                <!-- 🔥 TOTAL -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total</label>
                    <input type="text" id="total_transaksi" class="form-control bg-light" readonly>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Belum Dihubungi">🔴 Belum Dihubungi</option>
                        <option value="Belum Bayar">🟡 Belum Bayar</option>
                        <option value="Sudah Bayar">🟢 Sudah Bayar</option>
                    </select>
                </div>

                <!-- Catatan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3"></textarea>
                </div>

                <!-- 🔥 TANGGAL (FIX NAMA FIELD) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Tanggal Follow-Up</label>
                    <input type="datetime-local"
                        name="follow_up_date"
                        class="form-control"
                        value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <hr>

                <!-- Tombol -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        Simpan
                    </button>

                    <a href="{{ route('followups.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- 🔥 SCRIPT -->
<script>
const transaksiSelect = document.getElementById('transaksi_select');
const totalField = document.getElementById('total_transaksi');
const produkList = document.getElementById('produk_list');

function updateData() {
    let selected = transaksiSelect.options[transaksiSelect.selectedIndex];

    if (!selected || selected.value === "") {
        produkList.innerHTML = '';
        totalField.value = '';
        return;
    }

    let total = selected.getAttribute('data-total');
    let produkData = selected.getAttribute('data-produk');

    let produk = [];
    try {
        produk = JSON.parse(produkData);
    } catch (e) {
        produk = [];
    }

    if (produk.length > 0) {
        let html = '';
        produk.forEach(p => {
            html += `<span class="badge bg-primary me-1">${p.nama}</span> x${p.qty}<br>`;
        });
        produkList.innerHTML = html;
    } else {
        produkList.innerHTML = '<i>Tidak ada produk</i>';
    }

    if (total) {
        totalField.value = 'Rp ' + parseInt(total).toLocaleString('id-ID');
    } else {
        totalField.value = '';
    }
}

transaksiSelect.addEventListener('change', updateData);
</script>

@endsection
