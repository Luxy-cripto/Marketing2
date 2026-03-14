@extends('layouts.admin2')

@section('content')

<div class="container">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">📋 Daftar Follow-Up</h4>

    <a href="{{ route('followups.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Follow-Up
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<!-- TABLE FOLLOW UP -->
<div class="card shadow-sm">

<div class="card-header">
    <h4 class="card-title mb-0">Data Follow-Up Konsumen</h4>
</div>

<div class="card-body">

<div class="table-responsive">

<table class="display table table-striped table-hover">

<thead>
<tr>
    <th width="60">No</th>
    <th>Konsumen</th>
    <th>Status</th>
    <th>Catatan</th>
    <th>Tanggal Follow-Up</th>
    <th>User</th>
    <th width="150">Aksi</th>
</tr>
</thead>

<tbody>

@forelse($followUps as $i => $f)

<tr>

<td>{{ $i + 1 }}</td>

<td>
{{ $f->konsumen ? $f->konsumen->nama : '-' }}
</td>

<td>
<span class="badge bg-info">
{{ $f->status }}
</span>
</td>

<td>
{{ $f->catatan ?? '-' }}
</td>

<td>

@if($f->follow_up_date)

{{ \Carbon\Carbon::parse($f->follow_up_date)->format('d M Y H:i') }}

@else
-
@endif

</td>

<td>
{{ $f->user ? $f->user->name : '-' }}
</td>

<td>

<a href="{{ route('followups.edit', $f->id) }}"class="btn btn-warning btn-sm">
<i class="fa fa-edit"></i>
</a>

<form action="{{ route('followups.destroy', $f->id) }}"
method="POST"
class="d-inline"
onsubmit="return confirm('Yakin ingin hapus data ini?')">

@csrf
@method('DELETE')
<button type="submit" class="btn btn-danger btn-sm">
<i class="fa fa-trash"></i>
</button>
</form>
</td>
</tr>

@empty

<tr>
<td colspan="7" class="text-center">Tidak ada follow-up</td>
</tr>

@endforelse

</tbody>

</table>

</div>
</div>
</div>

</div>

@endsection
