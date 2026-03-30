@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0">

        <!-- Header -->
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-semibold">✏️ Edit Follow-Up</h4>
        </div>

        <div class="card-body">

            <!-- Error -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORM -->
           <form action="{{ route('followups.update', $followUp) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Konsumen -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Konsumen</label>
                    <select name="konsumen_id" class="form-select" required>
                        <option value="">-- Pilih Konsumen --</option>

                        @foreach($konsumens as $k)
                            <option value="{{ $k->id }}"
                                {{ old('konsumen_id', $followUp->konsumen_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Follow Up</label>
                    <select name="status" class="form-select" required>

                        <option value="Belum Dihubungi"
                            {{ old('status', $followUp->status) == 'Belum Dihubungi' ? 'selected' : '' }}>
                            🔴 Belum Dihubungi
                        </option>

                        <option value="Belum Bayar"
                            {{ old('status', $followUp->status) == 'Belum bayar' ? 'selected' : '' }}>
                            🟡 Belum Bayar
                        </option>

                        <option value="Sudah Bayar"
                            {{ old('status', $followUp->status) == 'Sudah bayar' ? 'selected' : '' }}>
                            🟢 Sudah Bayar
                        </option>

                    </select>
                </div>

                <!-- Catatan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $followUp->catatan) }}</textarea>
                </div>

                <!-- Tanggal -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Tanggal Follow Up</label>
                    <input type="datetime-local"
                        name="follow_up_date"
                        class="form-control"
                        value="{{ old('follow_up_date', $followUp->follow_up_date ? date('Y-m-d\TH:i', strtotime($followUp->follow_up_date)) : '') }}">
                </div>

                <hr>

                <!-- Tombol -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        Update
                    </button>

                    <a href="{{ route('followups.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
