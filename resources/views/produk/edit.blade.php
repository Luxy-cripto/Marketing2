@extends('layouts.admin2')

@section('content')

<div class="container">
    <h4>Edit Produk</h4>

    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   value="{{ old('nama', $produk->nama) }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi"
                      class="form-control"
                      rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="text"
                   id="harga"
                   name="harga"
                   class="form-control"
                   value="{{ old('harga', number_format($produk->harga, 0, ',', '.')) }}"
                   placeholder="Masukkan harga"
                   required>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number"
                   name="stok"
                   class="form-control"
                   value="{{ old('stok', $produk->stok) }}"
                   min="0"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const hargaInput = document.getElementById('harga');

    hargaInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value !== '') {
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            e.target.value = '';
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        hargaInput.value = hargaInput.value.replace(/\./g, '');
    });

});
</script>

@endsection
