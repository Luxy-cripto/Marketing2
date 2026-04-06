@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-semibold">🎯 Tambah Target Marketing</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('targets.store') }}" method="POST">
                @csrf

                <!-- USER -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">User Marketing</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih Marketing --</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- BULAN & TAHUN -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Bulan</label>
                        <input type="number" name="bulan" class="form-control" min="1" max="12" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" min="2000" required>
                    </div>
                </div>

                <!-- DETAIL PRODUK -->
                <hr>
                <h5 class="mb-3">📦 Target Per Produk</h5>

                @if($produks->isEmpty())
                    <div class="alert alert-warning">
                        Semua produk sudah memiliki target.
                    </div>
                @else

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Target Lead</th>
                                <th>Target Omset</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($produks as $produk)
                            <tr>
                                <td>{{ $produk->nama }}</td>

                                <td>
                                    <input type="number"
                                        name="produk[{{ $produk->id }}][qty]"
                                        class="form-control"
                                        placeholder="0">
                                </td>

                                <td>
                                    <input type="number"
                                        name="produk[{{ $produk->id }}][omset]"
                                        class="form-control"
                                        placeholder="0">
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                @endif

                <hr>

                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('targets.index') }}" class="btn btn-secondary">Batal</a>

            </form>

        </div>
    </div>

</div>
@endsection
