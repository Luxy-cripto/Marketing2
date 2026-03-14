@extends('layouts.admin2')

@section('content')
<div class="container">
    <h4>Edit Produk</h4>

    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $produk->harga) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ old('stok', $produk->stok) }}" min="0" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
