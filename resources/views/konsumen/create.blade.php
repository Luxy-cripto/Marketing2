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
                            @foreach(['Website', 'Instagram', 'Facebook', 'WhatsApp'] as $source)
                                <option value="{{ $source }}" {{ old('sumber_lead') == $source ? 'selected' : '' }}>
                                    {{ $source }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['Prospek', 'Deal', 'Tidak Tertarik'] as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 🔥 PRODUK MODERN -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Produk</label>

                        <div class="row">
                            @foreach($produks as $p)
                                <div class="col-md-4 mb-3">

                                    <div class="card produk-card h-100
                                                {{ collect(old('produk_id'))->contains($p->id) ? 'active' : '' }}"
                                        data-id="{{ $p->id }}">

                                        <div class="card-body text-center">
                                            <h6 class="fw-bold">{{ $p->nama }}</h6>
                                            <p class="text-muted mb-0">
                                                Rp {{ number_format($p->harga) }}
                                            </p>
                                        </div>

                                    </div>

                                    <!-- Hidden checkbox -->
                                    <input type="checkbox" name="produk_id[]" value="{{ $p->id }}"
                                        class="d-none produk-checkbox" {{ collect(old('produk_id'))->contains($p->id) ? 'checked' : '' }}>

                                </div>
                            @endforeach
                        </div>

                        <small class="text-muted">Klik produk untuk memilih</small>
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

    <style>
        .produk-card {
            cursor: pointer;
            border-radius: 14px;
            transition: all 0.25s ease;
            border: 2px solid transparent;
        }

        .produk-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .produk-card.active {
            border: 2px solid #0d6efd;
            background: #eff6ff;
        }
    </style>

    <!-- 🔥 SELECT2 -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <script>
        document.querySelectorAll('.produk-card').forEach(card => {

            card.addEventListener('click', function () {

                let checkbox = card.parentElement.querySelector('.produk-checkbox');

                card.classList.toggle('active');
                checkbox.checked = !checkbox.checked;

            });

        });
    </script>

@endsection
