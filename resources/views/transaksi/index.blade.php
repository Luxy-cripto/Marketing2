@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">📋 Data Transaksi</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('export.produk.terlaris') }}" class="btn btn-primary">
                📦 Export Produk
            </a>

            <a href="{{ route('transaksi.create') }}" class="btn btn-dark">
                ➕ Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h3 id="totalTransaksi">{{ $transaksis->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small>Total Omzet</small>
                    <h4 id="totalOmzet">
                        Rp {{ number_format($totalOmzet,0,',','.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <small>Produk Terjual</small>
                    <h4>{{ $totalProduk }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">🔎 Cari</label>
                    <input type="text" id="searchTransaksi" class="form-control"
                        placeholder="Nama Konsumen / Produk / No HP">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Filter</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">Semua</option>
                        <option value="hari">Hari Ini</option>
                        <option value="minggu">7 Hari</option>
                        <option value="bulan">Bulan Ini</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">📅 Tanggal</label>
                    <input type="date" id="filterTanggal" class="form-control">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-secondary w-100" id="resetFilter">
                        Reset
                    </button>
                </div>

                <div class="col-md-2">
                    <label class="form-label invisible">Export</label>
                    <a href="#"
                        onclick="exportData()"
                        class="btn btn-success w-100">
                        📥 Export
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- TABLE TRANSAKSI -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tableTransaksi">

                    <thead class="table-secondary">
                        <tr>
                            <th>Konsumen</th>
                            <th>No HP</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transaksis as $t)
                        <tr data-tanggal="{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('Y-m-d') }}" data-total="{{ $t->total }}">
                            <td>{{ $t->konsumen?->nama ?? '-' }}</td>
                            <td>{{ $t->konsumen?->no_hp ?? '-' }}</td>

                            <td>
                                {{ $t->details->pluck('produk.nama')->join(', ') }}
                            </td>

                            <td>
                                {{ $t->details->pluck('qty')->join(', ') }}
                            </td>

                            <td>
                                {{ $t->details->pluck('harga_satuan')->map(fn($h)=> 'Rp '.number_format($h,0,',','.'))->join(', ') }}
                            </td>

                            <td>Rp {{ number_format($t->total,0,',','.') }}</td>

                            <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('Y-m-d') }}</td>

                            <td>
                                @if(strtolower($t->status) == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Bayar</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('transaksi.invoice', $t->id) }}" class="btn btn-sm btn-primary" title="Invoice">📄</a>
                                    <a href="{{ route('transaksi.edit', $t->id) }}" class="btn btn-sm btn-warning" title="Edit">✏️</a>
                                    <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">🗑</button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <!-- PRODUK TERLARIS -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <strong>🔥 Produk Terlaris</strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-secondary">
                        <tr>
                            <th>Produk</th>
                            <th>Total Terjual</th>
                            <th>Total Omzet</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($produkTerlaris as $p)
                        <tr>
                            <td>{{ $p->nama }}</td>
                            <td><strong>{{ $p->total_qty }}</strong></td>
                            <td>Rp {{ number_format($p->total_omzet,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Tidak ada data produk
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<script>
let searchInput = document.getElementById("searchTransaksi")
let filterTanggal = document.getElementById("filterTanggal")
let filterStatus = document.getElementById("filterStatus")
let resetBtn = document.getElementById("resetFilter")

let totalTransaksi = document.getElementById("totalTransaksi")
let totalOmzet = document.getElementById("totalOmzet")

function filterTable() {
    let keyword = searchInput.value.toLowerCase()
    let tanggal = filterTanggal.value
    let status = filterStatus.value

    let rows = document.querySelectorAll("#tableTransaksi tbody tr")
    let total = 0
    let count = 0
    let today = new Date()
    let todayStr = today.toISOString().split('T')[0]

    rows.forEach(function(row){
        let text = row.innerText.toLowerCase()
        let rowTanggal = row.getAttribute("data-tanggal")
        let rowTotal = parseFloat(row.getAttribute("data-total"))

        let matchSearch = text.includes(keyword)
        let matchTanggal = !tanggal || rowTanggal === tanggal
        let matchStatus = true

        if(status === "hari"){
            matchStatus = rowTanggal === todayStr
        }
        if(status === "minggu"){
            let d = new Date(rowTanggal)
            let diff = (today - d) / (1000*60*60*24)
            matchStatus = diff <= 7
        }
        if(status === "bulan"){
            let d = new Date(rowTanggal)
            matchStatus = d.getMonth() === today.getMonth() && d.getFullYear() === today.getFullYear()
        }

        if(matchSearch && matchTanggal && matchStatus){
            row.style.display = ""
            total += rowTotal
            count++
        } else {
            row.style.display = "none"
        }
    })

    totalTransaksi.innerText = count
    totalOmzet.innerText = "Rp " + total.toLocaleString("id-ID")
}

searchInput.addEventListener("keyup", filterTable)
filterTanggal.addEventListener("change", filterTable)
filterStatus.addEventListener("change", filterTable)

resetBtn.addEventListener("click", function(){
    searchInput.value = ""
    filterTanggal.value = ""
    filterStatus.value = ""
    filterTable()
})

// auto load
filterTable()

function exportData() {
    let tanggal = document.getElementById('filterTanggal').value
    let search = document.getElementById('searchTransaksi').value
    let url = "{{ route('export.transaksi') }}?1=1"

    if(tanggal){
        url += "&start_date=" + tanggal + "&end_date=" + tanggal
    }
    if(search){
        url += "&search=" + encodeURIComponent(search)
    }
    window.location.href = url
}
</script>

@endsection
