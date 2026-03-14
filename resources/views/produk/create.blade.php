@extends('layouts.admin2')

@section('content')

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Tambah Produk</h4>

    <a href="{{ route('produk.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
<div class="card-body">

<form action="{{ route('produk.store') }}" method="POST">
@csrf

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Nama Produk</label>
<input type="text"
name="nama"
class="form-control"
value="{{ old('nama') }}"
placeholder="Masukkan nama produk"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Harga</label>
<input type="number"
name="harga"
class="form-control"
value="{{ old('harga') }}"
min="0"
placeholder="Masukkan harga"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Stok</label>
<input type="number"
name="stok"
class="form-control"
value="{{ old('stok',0) }}"
min="0"
placeholder="Jumlah stok"
required>
</div>

<div class="col-md-12 mb-3">
<label class="form-label">Deskripsi</label>
<textarea
name="deskripsi"
class="form-control"
rows="4"
placeholder="Deskripsi produk (opsional)">{{ old('deskripsi') }}</textarea>
</div>

</div>

<hr>

<div class="d-flex gap-2">

<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Simpan
</button>

<a href="{{ route('produk.index') }}" class="btn btn-light">
Batal
</a>

</div>

</form>

</div>
</div>

</div>

@endsection
