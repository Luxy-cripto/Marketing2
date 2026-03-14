@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold">➕ Tambah Konsumen</h4>
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

            <form action="{{ route('konsumen.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sumber Lead</label>
                    <select name="sumber_lead" class="form-select">
                        <option value="">Pilih Sumber Lead</option>
                        @foreach(['Website','Instagram','Facebook','WhatsApp'] as $source)
                            <option value="{{ $source }}" {{ old('sumber_lead')==$source ? 'selected' : '' }}>
                                {{ $source }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['Prospek','Deal','Tidak Tertarik'] as $status)
                            <option value="{{ $status }}" {{ old('status')==$status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
