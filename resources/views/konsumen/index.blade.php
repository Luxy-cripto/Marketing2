@extends('layouts.admin2')

@section('content')
<div class="container">

    <h4 class="mb-3">Data Konsumen</h4>

    <a href="{{ route('konsumen.create') }}" class="btn btn-success mb-3">
        + Tambah Konsumen
    </a>

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
                    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>

            <div id="loading" class="text-center mt-3" style="display:none;">
                <div class="spinner-border text-secondary"></div>
                <div class="small">Loading...</div>
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
                                    <span class="badge bg-secondary">
                                        {{ $k->status }}
                                    </span>
                                </td>

                                <td>{{ $k->user->name ?? '-' }}</td>

                                <td>{{ $k->alamat ?? '-' }}</td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="{{ route('konsumen.edit',$k->id) }}" class="btn btn-warning btn-sm">
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

</div>
@endsection
