@extends('layouts.admin2')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📋 Daftar Follow-Up</h4>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- CARD -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold">Data Follow-Up Konsumen</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Konsumen</th>
                            <th width="250">Produk Dibeli</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th width="160">Tanggal Follow-Up</th>
                            <th>User</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($followUps as $i => $f)

                            @php
                                $status = $f->status;

                                $badge = match($status) {
                                    'Belum Dihubungi' => 'warning text-dark',
                                    'Belum Bayar' => 'secondary',
                                    'Sudah Bayar' => 'success',
                                    default => 'secondary',
                                };

                                $pesan = "Halo ".$f->konsumen->nama.", kami dari tim marketing 😊%0A";

                                if($status == 'Belum Dihubungi'){
                                    $pesan .= "Kami ingin follow up terkait penawaran kami.";
                                } elseif($status == 'Belum Bayar'){
                                    $pesan .= "Kami ingin mengingatkan bahwa transaksi Anda belum dibayar.";
                                }

                                $link = "https://wa.me/".$f->konsumen->no_hp."?text=".$pesan;
                            @endphp

                            <tr>
                                <!-- No -->
                                <td class="text-center">{{ $i + 1 }}</td>

                                <!-- Konsumen -->
                                <td class="fw-semibold">
                                    {{ $f->konsumen->nama ?? '-' }}
                                </td>

                                <!-- Produk -->
                                <td style="max-width:250px;">
                                    @if($f->transaksi && $f->transaksi->details->count())
                                        <ul class="mb-0 ps-3" style="font-size: 13px;">
                                            @foreach($f->transaksi->details as $d)
                                                <li>
                                                    {{ $d->produk->nama ?? '-' }}
                                                    <span class="text-muted">(jumlah: {{ $d->qty }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Tidak ada produk</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="text-center">
                                    <span class="badge bg-{{ $badge }} px-3 py-2">
                                        {{ $status }}
                                    </span>
                                </td>

                                <!-- Catatan -->
                                <td style="max-width:200px;">
                                    <span class="text-muted">
                                        {{ $f->catatan ?? '-' }}
                                    </span>
                                </td>

                                <!-- Tanggal -->
                                <td class="text-center">
                                    @if($f->follow_up_date)
                                        <small>
                                            {{ \Carbon\Carbon::parse($f->follow_up_date)->format('d M Y') }}<br>
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($f->follow_up_date)->format('H:i') }}
                                            </span>
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>

                                <!-- User -->
                                <td class="text-center">
                                    {{ $f->user->name ?? '-' }}
                                </td>

                                <!-- Aksi -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('followups.edit', $f) }}"
                                           class="btn btn-warning btn-sm shadow-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{ route('followups.destroy', $f->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                        <a href="{{ $link }}" target="_blank"
                                           class="btn btn-success btn-sm shadow-sm">
                                            💬
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Tidak ada follow-up
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
