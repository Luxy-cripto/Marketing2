@extends('layouts.admin2')

@section('content')

<div class="container">

<div class="card">
<div class="card-header">
<h4 class="card-title">➕ Tambah Transaksi</h4>
</div>

<div class="card-body">

<form action="{{ route('transaksi.store') }}" method="POST">
@csrf

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Konsumen</label>

<select name="konsumen_id" class="form-control" required>
<option value="">-- Pilih Konsumen --</option>

@foreach($konsumens as $k)
<option value="{{ $k->id }}">
{{ $k->nama }}
</option>
@endforeach

</select>
</div>


<div class="col-md-6 mb-3">
<label class="form-label">Produk</label>

<select name="produk_id" class="form-control" required>
<option value="">-- Pilih Produk --</option>

@foreach($produks as $p)
<option value="{{ $p->id }}">
{{ $p->nama }} - Rp {{ number_format($p->harga,0,',','.') }}
</option>
@endforeach

</select>
</div>


<div class="col-md-6 mb-3">
<label class="form-label">Jumlah</label>

<input
type="number"
name="qty"
class="form-control"
min="1"
value="1"
required>
</div>


</div>


<div class="mt-3">

<button class="btn btn-success">
Simpan
</button>

<a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
Batal
</a>

</div>

</form>

</div>
</div>

</div>

@endsection
