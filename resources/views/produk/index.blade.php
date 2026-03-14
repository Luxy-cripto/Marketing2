@extends('layouts.admin2')

@section('content')

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Daftar Produk</h4>

    <a href="{{ route('produk.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Produk
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif


<div class="card shadow-sm">
<div class="card-body">

<div class="table-responsive">

<table id="multi-filter-select" class="table table-hover align-middle">

<thead class="border-bottom">
<tr>
    <th>Nama Produk</th>
    <th>Deskripsi</th>
    <th>Harga</th>
    <th>Stok</th>
    <th class="text-center" width="150">Aksi</th>
</tr>
</thead>

<tbody>

@foreach($produks as $p)
<tr>

<td class="fw-semibold">
    {{ $p->nama }}
</td>

<td>
    {{ $p->deskripsi ?? '-' }}
</td>

<td class="text-success fw-bold">
Rp {{ number_format($p->harga,0,',','.') }}
</td>

<td>
<span class="badge bg-info">
{{ $p->stok }}
</span>
</td>

<td class="text-center">

<a href="{{ route('produk.edit',$p->id) }}"
class="btn btn-warning btn-sm">

<i class="fa fa-edit"></i>
</a>

<form action="{{ route('produk.destroy',$p->id) }}"
method="POST"
class="d-inline">

@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm">
<i class="fa fa-trash"></i>
</button>

</form>

</td>

</tr>
@endforeach

</tbody>

</table>

</div>
</div>
</div>

</div>

@endsection
