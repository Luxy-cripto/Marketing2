@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0">

        <!-- Header -->
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-semibold">✏️ Edit Follow-Up</h4>
        </div>

        <div class="card-body">

            {{-- ERROR --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('followups.update', $followUp->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- KONSUMEN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Konsumen</label>
                    <select name="konsumen_id" class="form-select" required>
                        <option value="" disabled>-- Pilih Konsumen --</option>
                        @foreach($konsumens as $k)
                            <option value="{{ $k->id }}"
                                {{ old('konsumen_id', $followUp->konsumen_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TRANSAKSI --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Transaksi / Invoice</label>
                    <select name="transaksi_id" id="transaksi_select" class="form-select" required>
                        <option value="" disabled>-- Pilih Transaksi --</option>

                        @foreach($transaksis as $trx)
                            <option value="{{ $trx->id }}"
                                data-total="{{ $trx->total ?? 0 }}"
                                data-produk='@json(
                                    $trx->details->map(function($d){
                                        return [
                                            "nama" => $d->produk->nama ?? "-",
                                            "qty" => $d->qty ?? 0
                                        ];
                                    })
                                )'
                                {{ old('transaksi_id', $followUp->transaksi_id) == $trx->id ? 'selected' : '' }}>

                                INV-{{ $trx->id }} |
                                {{ $trx->konsumen->nama ?? '-' }} |
                                {{ $trx->details->count() }} barang |
                                Rp {{ number_format($trx->total ?? 0) }}

                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PRODUK --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Produk Dibeli</label>
                    <div id="produk_list" class="form-control bg-light" style="min-height:80px"></div>
                </div>

                {{-- TOTAL --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Transaksi</label>
                    <input type="text" id="total_transaksi" class="form-control bg-light" readonly>
                </div>

                {{-- STATUS --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Belum Dihubungi" {{ $followUp->status == 'Belum Dihubungi' ? 'selected' : '' }}>🔴 Belum Dihubungi</option>
                        <option value="Belum Bayar" {{ $followUp->status == 'Belum Bayar' ? 'selected' : '' }}>🟡 Belum Bayar</option>
                        <option value="Sudah Bayar" {{ $followUp->status == 'Sudah Bayar' ? 'selected' : '' }}>🟢 Sudah Bayar</option>
                    </select>
                </div>

                {{-- CATATAN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $followUp->catatan) }}</textarea>
                </div>

                {{-- TANGGAL --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Tanggal Follow-Up</label>
                    <input type="datetime-local"
                        name="follow_up_date"
                        class="form-control"
                        value="{{ old('follow_up_date', $followUp->follow_up_date ? date('Y-m-d\TH:i', strtotime($followUp->follow_up_date)) : '') }}">
                </div>

                <hr>

                {{-- BUTTON --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">💾 Update</button>
                    <a href="{{ route('followups.index') }}" class="btn btn-outline-secondary">⬅️ Kembali</a>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- SCRIPT --}}
<script>
const transaksiSelect = document.getElementById('transaksi_select');
const totalField = document.getElementById('total_transaksi');
const produkList = document.getElementById('produk_list');

function updateData() {
    let selected = transaksiSelect.options[transaksiSelect.selectedIndex];

    if (!selected || selected.value === "") {
        produkList.innerHTML = '<i class="text-muted">Pilih transaksi dulu</i>';
        totalField.value = '';
        return;
    }

    let total = selected.getAttribute('data-total') || 0;
    let produkData = selected.getAttribute('data-produk');

    let produk = [];
    try {
        produk = JSON.parse(produkData);
    } catch (e) {
        produk = [];
    }

    // tampil produk
    if (produk.length > 0) {
        let html = '';
        produk.forEach(p => {
            html += `<span class="badge bg-primary me-1">${p.nama}</span> x${p.qty}<br>`;
        });
        produkList.innerHTML = html;
    } else {
        produkList.innerHTML = '<i class="text-muted">Tidak ada produk</i>';
    }

    // tampil total
    totalField.value = 'Rp ' + parseInt(total).toLocaleString('id-ID');
}

// event change
transaksiSelect.addEventListener('change', updateData);

// auto load saat edit
document.addEventListener('DOMContentLoaded', updateData);
</script>

@endsection
