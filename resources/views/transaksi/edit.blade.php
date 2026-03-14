@extends('layouts.admin2')

@section('content')

<div class="container">

<div class="card">
<div class="card-header">
<h4 class="card-title">✏️ Edit Transaksi</h4>
</div>

<div class="card-body">

<form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">

@csrf
@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Konsumen</label>

<select name="konsumen_id" class="form-control" required>

@foreach($konsumens as $k)

<option value="{{ $k->id }}"
{{ $transaksi->konsumen_id == $k->id ? 'selected' : '' }}>

{{ $k->nama }}

</option>

@endforeach

</select>
</div>


<div class="col-md-6 mb-3">
<label class="form-label">Produk</label>

<select name="produk_id" class="form-control" required>

@foreach($produks as $p)

<option value="{{ $p->id }}"
{{ $transaksi->produk_id == $p->id ? 'selected' : '' }}>

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
value="{{ $transaksi->qty }}"
required>

</div>


</div>


<div class="mt-3">

<button class="btn btn-success">
Update
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
