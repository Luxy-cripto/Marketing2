@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-light">
            <h4 class="mb-0 fw-semibold">
                ✏️ Edit Target Marketing
            </h4>
        </div>

        <div class="card-body">

            <form action="{{ route('targets.update', $target->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- User Marketing -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        User Marketing
                    </label>

                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih Marketing --</option>

                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}"
                                {{ $target->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- Bulan & Tahun -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Bulan
                        </label>

                        <input
                            type="number"
                            name="bulan"
                            class="form-control"
                            min="1"
                            max="12"
                            value="{{ $target->bulan }}"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Tahun
                        </label>

                        <input
                            type="number"
                            name="tahun"
                            class="form-control"
                            min="2000"
                            value="{{ $target->tahun }}"
                            required
                        >
                    </div>

                </div>

                <!-- Target Omset -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Target Omset
                    </label>

                    <input
                        type="number"
                        name="target_omset"
                        class="form-control"
                        min="0"
                        value="{{ $target->target_omset }}"
                        required
                    >
                </div>

                <!-- Target Lead -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Target Lead
                    </label>

                    <input
                        type="number"
                        name="target_lead"
                        class="form-control"
                        min="0"
                        value="{{ $target->target_lead }}"
                        required
                    >
                </div>

                <hr>

                <!-- Tombol -->
                <div class="d-flex gap-2">

                    <button class="btn btn-success px-4">
                        Update
                    </button>

                    <a href="{{ route('targets.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection
