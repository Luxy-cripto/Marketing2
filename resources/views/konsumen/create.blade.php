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

                <!-- Nama -->
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <!-- No HP -->
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>

                <!-- Sumber Lead -->
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

                <!-- Status -->
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

                <!-- 🔥 PRODUK MULTIPLE -->
                <div class="mb-3">
                    <label class="form-label">Produk</label>
                    <select name="produk_id[]" class="form-select select2" multiple>
                        @foreach($produks as $p)
                            <option value="{{ $p->id }}"
                                {{ collect(old('produk_id'))->contains($p->id) ? 'selected' : '' }}>
                                {{ $p->nama }} - Rp {{ number_format($p->harga) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Bisa pilih lebih dari satu produk</small>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- 🔥 SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih produk",
            width: '100%'
        });
    });
</script>

@endsection
