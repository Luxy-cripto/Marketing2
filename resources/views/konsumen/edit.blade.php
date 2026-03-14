@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold">✏️ Edit Konsumen</h4>
    </div>

    <!-- ERROR ALERT -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- FORM EDIT -->
            <form action="{{ route('konsumen.update', $konsumen) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="{{ old('nama', $konsumen->nama) }}"
                        required
                    >
                </div>

                <!-- NO HP -->
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input
                        type="text"
                        name="no_hp"
                        class="form-control"
                        value="{{ old('no_hp', $konsumen->no_hp) }}"
                        required
                    >
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $konsumen->email) }}"
                    >
                </div>

                <!-- ALAMAT -->
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="2"
                    >{{ old('alamat', $konsumen->alamat) }}</textarea>
                </div>

                <!-- SUMBER LEAD -->
                <div class="mb-3">
                    <label class="form-label">Sumber Lead</label>
                    <select name="sumber_lead" class="form-select">
                        <option value="">Pilih Sumber Lead</option>

                        @foreach(['Website','Instagram','Facebook','WhatsApp'] as $source)
                            <option value="{{ $source }}"
                                {{ old('sumber_lead', $konsumen->sumber_lead) == $source ? 'selected' : '' }}>
                                {{ $source }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">

                        @foreach(['Prospek','Deal','Tidak Tertarik'] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $konsumen->status) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>

                    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">
                        Batal
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection
