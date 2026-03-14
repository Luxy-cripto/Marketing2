@extends('layouts.admin2')

@section('content')

<div class="container">

        <h4 class="mb-3">Data Konsumen</h4>

        <a href="{{ route('konsumen.create') }}" class="btn btn-success mb-3">
                + Tambah Konsumen
        </a>


        <div class="d-flex align-items-center gap-3 mb-3">

                <!-- IMPORT -->

                <form
                        action="{{ route('konsumen.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="d-flex align-items-center gap-2"
                >

                        @csrf

                        <input
                                type="file"
                                name="file"
                                class="form-control form-control-sm"
                                style="width:200px;"
                                required
                        >

                        <button class="btn btn-success btn-sm rounded-pill px-3">
                                Import
                        </button>

                </form>


                <!-- EXPORT -->

                <form
                        action="{{ route('konsumen.export') }}"
                        method="GET"
                        class="d-flex align-items-center gap-2"
                >

                        <select
                                name="status"
                                class="form-select form-select-sm"
                                style="width:160px;"
                        >

                                <option value="">Semua</option>
                                <option value="Prospek">Prospek</option>
                                <option value="Deal">Deal</option>
                                <option value="Tidak Tertarik">Tidak Tertarik</option>

                        </select>

                        <button class="btn btn-success btn-sm rounded-pill px-3">
                                Export
                        </button>

                </form>

        </div>


        @if(session('success'))

                <div class="alert alert-success">
                        {{ session('success') }}
                </div>

        @endif



        <!-- FILTER -->

        <div class="card mb-3">

                <div class="card-body">

                        <div class="row">

                                <div class="col-md-4">

                                        <input
                                                type="text"
                                                id="search"
                                                class="form-control"
                                                placeholder="Cari nama / no HP..."
                                        >

                                </div>


                                <div class="col-md-3">

                                        <select id="status" class="form-select">

                                                <option value="">Semua Status</option>
                                                <option value="Prospek">Prospek</option>
                                                <option value="Deal">Deal</option>
                                                <option value="Tidak Tertarik">Tidak Tertarik</option>

                                        </select>

                                </div>


                                <div class="col-md-2">

                                        <a
                                                href="{{ route('konsumen.index') }}"
                                                class="btn btn-secondary w-100"
                                        >
                                                Reset
                                        </a>

                                </div>

                        </div>


                        <div
                                id="loading"
                                class="text-center mt-3"
                                style="display:none;"
                        >

                                <div class="spinner-border text-secondary"></div>

                                <div class="small">
                                        Loading...
                                </div>

                        </div>

                </div>

        </div>



        <!-- TABLE -->

        <div class="card">

                <div class="card-body">

                        <div class="table-responsive">

                                <table class="display table table-striped table-hover align-middle">

                                        <thead>

                                                <tr>

                                                        <th>Nama</th>
                                                        <th>No HP</th>
                                                        <th>Email</th>
                                                        <th>Sumber</th>
                                                        <th>Status</th>
                                                        <th>Marketing</th>
                                                        <th>Alamat</th>
                                                        <th width="150">Aksi</th>

                                                </tr>

                                        </thead>


                                        <tbody id="konsumen-table">

                                                @forelse($konsumens as $k)

                                                        <tr>

                                                                <td>{{ $k->nama }}</td>
                                                                <td>{{ $k->no_hp }}</td>
                                                                <td>{{ $k->email ?? '-' }}</td>
                                                                <td>{{ $k->sumber_lead ?? '-' }}</td>

                                                                <td>

                                                                        <span class="badge
                                                                                @if($k->status=='Prospek') bg-warning
                                                                                @elseif($k->status=='Deal') bg-success
                                                                                @else bg-danger
                                                                                @endif
                                                                        ">

                                                                                {{ $k->status }}

                                                                        </span>

                                                                </td>

                                                                <td>{{ $k->user->name ?? '-' }}</td>

                                                                <td>{{ $k->alamat ?? '-' }}</td>

                                                                <td>

                                                                        <div class="d-flex gap-2">

                                                                                <a
                                                                                        href="{{ route('konsumen.edit',$k->id) }}"
                                                                                        class="btn btn-warning btn-sm"
                                                                                >
                                                                                        Edit
                                                                                </a>

                                                                                <form
                                                                                        action="{{ route('konsumen.destroy',$k->id) }}"
                                                                                        method="POST"
                                                                                        onsubmit="return confirm('Yakin hapus?')"
                                                                                >

                                                                                        @csrf
                                                                                        @method('DELETE')

                                                                                        <button class="btn btn-danger btn-sm">
                                                                                                Hapus
                                                                                        </button>

                                                                                </form>

                                                                        </div>

                                                                </td>

                                                        </tr>

                                                @empty

                                                        <tr>

                                                                <td colspan="8" class="text-center text-muted">
                                                                        Tidak ada data konsumen
                                                                </td>

                                                        </tr>

                                                @endforelse

                                        </tbody>

                                </table>

                        </div>

                </div>

        </div>


        <div class="mt-3">

                {{ $konsumens->links() }}

        </div>

</div>



<!-- LIVE SEARCH SCRIPT -->

<script>

document.addEventListener("DOMContentLoaded", function(){

        const searchInput = document.getElementById("search");
        const statusFilter = document.getElementById("status");
        const tableBody = document.getElementById("konsumen-table");
        const loading = document.getElementById("loading");

        function loadData(){

                let search = searchInput.value;
                let status = statusFilter.value;

                loading.style.display = "block";

                fetch(`/konsumen/live-search?search=${search}&status=${status}`)
                .then(response => response.json())
                .then(data => {

                        loading.style.display = "none";

                        let html = "";

                        if(data.length === 0){

                                html = `
                                <tr>
                                        <td colspan="8" class="text-center text-muted">
                                                Tidak ada data
                                        </td>
                                </tr>
                                `;

                        }else{

                                data.forEach(k => {

                                        let badge = "bg-danger";

                                        if(k.status === "Prospek") badge = "bg-warning";
                                        if(k.status === "Deal") badge = "bg-success";

                                        html += `
                                        <tr>

                                                <td>${k.nama}</td>
                                                <td>${k.no_hp}</td>
                                                <td>${k.email ?? '-'}</td>
                                                <td>${k.sumber_lead ?? '-'}</td>

                                                <td>
                                                        <span class="badge ${badge}">
                                                                ${k.status}
                                                        </span>
                                                </td>

                                                <td>${k.user?.name ?? '-'}</td>

                                                <td>${k.alamat ?? '-'}</td>

                                                <td>
                                                        <a href="/konsumen/${k.id}/edit" class="btn btn-warning btn-sm">
                                                                Edit
                                                        </a>
                                                </td>

                                        </tr>
                                        `;

                                });

                        }

                        tableBody.innerHTML = html;

                });

        }

        searchInput.addEventListener("keyup", loadData);
        statusFilter.addEventListener("change", loadData);

});

</script>

@endsection
