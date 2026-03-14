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
                    <label class="form-label fw-semibold">
                        Konsumen
                    </label>

                    <select name="konsumen_id" class="form-select" required>
                        <option value="">-- Pilih Konsumen --</option>

                        @foreach($konsumens as $k)
                        <option value="{{ $k->id }}"
                            {{ old('konsumen_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                        @endforeach

                    </select>
                </div>


                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select name="status" class="form-select" required>

                        @php
                            $statuses = ['Belum Dihubungi', 'Follow-Up', 'Deal'];
                        @endphp

                        @foreach($statuses as $status)
                        <option value="{{ $status }}"
                            {{ old('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach

                    </select>
                </div>


                <!-- Catatan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Catatan
                    </label>

                    <textarea
                        name="catatan"
                        class="form-control"
                        rows="3"
                    >{{ old('catatan') }}</textarea>
                </div>


                <!-- Tanggal Follow Up -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Tanggal Follow-Up
                    </label>

                    <input
                        type="datetime-local"
                        name="tanggal_followup"
                        class="form-control"
                        value="{{ old('tanggal_followup', now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i')) }}"
                    >
                </div>


                <hr>


                <!-- Tombol -->
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-success px-4">
                        Simpan
                    </button>

                    <a href="{{ route('followups.index') }}"
                       class="btn btn-outline-secondary">
                        Batal
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection
