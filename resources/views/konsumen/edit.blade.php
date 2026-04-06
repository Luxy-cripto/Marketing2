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
            <form action="{{ route('konsumen.update', $konsumen->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control"
                        value="{{ old('nama', $konsumen->nama) }}" required>
                </div>

                <!-- NO HP -->
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="{{ old('no_hp', $konsumen->no_hp) }}" required>
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $konsumen->email) }}">
                </div>

                <!-- ALAMAT -->
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $konsumen->alamat) }}</textarea>
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

                <!-- 🔥 PRODUK MULTIPLE -->
                <div class="mb-3">
                    <label class="form-label">Produk Diminati</label>
                    <select name="produk_id[]" class="form-select select2" multiple>

                        @foreach($produks as $p)
                            <option value="{{ $p->id }}"
                                @if(collect(old('produk_id', $konsumen->produks->pluck('id')))->contains($p->id)) selected @endif>
                                {{ $p->nama }} - Rp {{ number_format($p->harga) }}
                            </option>
                        @endforeach

                    </select>
                    <small class="text-muted">Bisa pilih lebih dari satu produk</small>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
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
