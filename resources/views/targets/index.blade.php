@extends('layouts.admin2')

@section('content')
<div class="container-fluid">

    <h2 class="mb-4">Target Marketing</h2>

    <a href="{{ route('targets.create') }}" class="btn btn-primary mb-3">
        Tambah Target
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- ================= TABEL TARGET ================= -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <b>Data Target Marketing</b>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Target Omset</th>
                            <th>Target Lead</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($targets as $index => $target)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $target->user->name ?? '-' }}</td>
                            <td>{{ $target->bulan }}</td>
                            <td>{{ $target->tahun }}</td>
                            <td>Rp {{ number_format($target->target_omset,0,',','.') }}</td>
                            <td>{{ $target->target_lead }}</td>
                            <td>
                                <a href="{{ route('targets.edit', $target->id) }}"
                                   class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('targets.destroy', $target->id) }}"
                                      method="POST"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Yakin hapus target ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada target</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TABEL DETAIL PRODUK ================= -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <b>Detail Target Produk</b>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Produk</th>
                            <th>Target Lead</th>
                            <th>Target Omset</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($targets as $target)

                            @foreach($target->details as $detail)
                                <tr>
                                    <td>{{ $target->user->name ?? '-' }}</td>
                                    <td>{{ $target->bulan }}</td>
                                    <td>{{ $target->tahun }}</td>

                                    <td>{{ $detail->produk->nama }}</td>

                                    <td>{{ $detail->target_qty }}</td>

                                    <td>
                                        Rp {{ number_format($detail->target_omset_produk,0,',','.') }}
                                    </td>
                                </tr>
                            @endforeach

                        @endforeach

                        @if($targets->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada data
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>
@endsection
