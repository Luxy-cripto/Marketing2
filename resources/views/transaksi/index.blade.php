@extends('layouts.admin2')

@section('content')

<div class="container py-10">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="fw-bold">📋 Data Transaksi</h4>

        <div class="d-flex gap-2">

            <a id="exportTransaksiBtn"
               href="{{ route('export.transaksi') }}"
               class="btn btn-success">
                📥 Export Transaksi
            </a>

            <a href="{{ route('export.produk.terlaris') }}"
               class="btn btn-primary">
                📦 Export Produk
            </a>

            <a href="{{ route('transaksi.create') }}"
               class="btn btn-dark">
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

                    <h3 id="totalTransaksi">
                        {{ count($transaksis) }}
                    </h3>

                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h6 class="text-muted">Total Omzet</h6>

                    <h3 id="totalOmzet">
                        Rp {{ number_format($transaksis->sum('total'),0,',','.') }}
                    </h3>

                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h6 class="text-muted">Produk Terjual</h6>

                    <h3>
                        {{ $transaksis->sum('qty') }}
                    </h3>

                </div>
            </div>
        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif



    <!-- FILTER -->
    <div class="card shadow-sm border-0 mb-5">

        <div class="card-body" style="background:#f8f9fa;">


              <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">🔎 Cari</label>
                    <input type="text"
                        id="searchTransaksi"
                        class="form-control"
                        placeholder="Nama / Produk / No HP">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Filter</label>
                    <select id="filterStatus" class="form-select">
                        <option value="semua">semua</option>
                        <option value="konsumen">konsumen</option>
                        <option value="produk">produk</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">📅 Tanggal</label>
                    <input type="date"
                        id="filterTanggal"
                        class="form-control">
                </div>

                <div class="col-md-1">
                    <button
                        class="btn btn-secondary w-100"
                        id="resetFilter">
                        Reset
                    </button>
                </div>

                <div class="col-md-2">
                    <label class="form-label invisible">Export</label>
                    <a href="{{ route('export.transaksi') }}"
                        class="btn btn-success w-100">
                        📥 Export
                    </a>
                </div>

            </div>

        </div>


    </div>



    <!-- TABLE TRANSAKSI -->
    <div class="card shadow-sm border-0 mb-5">

        <div class="card-header bg-light">
            <strong>Daftar Transaksi</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-striped table-hover align-middle"
                    id="tableTransaksi"
                >

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
                            <th width="150">Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($transaksis as $t)

                            <tr
                                data-tanggal="{{ $t->tanggal_transaksi }}"
                                data-total="{{ $t->total }}"
                            >

                                <td>{{ $t->konsumen->nama }}</td>

                                <td>{{ $t->konsumen->no_hp }}</td>

                                <td>{{ $t->produk->nama }}</td>

                                <td>{{ $t->qty }}</td>

                                <td>
                                    Rp {{ number_format($t->harga_satuan,0,',','.') }}
                                </td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($t->total,0,',','.') }}
                                    </strong>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('Y-m-d') }}
                                </td>

                                <td>
                                    <span class="badge
                                        {{ $t->status=='Deal'?'bg-success':($t->status=='Follow-Up'?'bg-warning':'bg-secondary') }}">
                                        {{ $t->status }}
                                    </span>
                                </td>

                                <td>


                                    <div class="d-flex gap-2">

                                        <a
                                            href="{{ route('transaksi.edit',$t->id) }}"
                                            class="btn btn-outline-warning btn-sm"
                                        >
                                            ✏️
                                        </a>

                                        <form
                                            action="{{ route('transaksi.destroy',$t->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin hapus?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="btn btn-outline-danger btn-sm"
                                            >
                                                🗑
                                            </button>

                                        </form>

                                    </div>

                                </td>
                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center text-muted py-4">

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

                        @foreach($produkTerlaris as $p)

                            <tr>

                                <td>{{ $p->nama }}</td>

                                <td>
                                    <strong>{{ $p->total_qty }}</strong>
                                </td>

                                <td>
                                    Rp {{ number_format($p->total_omzet,0,',','.') }}
                                </td>

                            </tr>

                        @endforeach

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
        let rowTotal = parseInt(row.getAttribute("data-total"))

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

            matchStatus = d.getMonth() === today.getMonth()

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

</script>

@endsection

