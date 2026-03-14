@extends('layouts.admin2')

@section('content')
<div class="container">
    <h3>Detail Konsumen</h3>

    <p><strong>Nama:</strong> {{ $konsumen->nama }}</p>
    <p><strong>No HP:</strong> {{ $konsumen->no_hp }}</p>
    <p><strong>Email:</strong> {{ $konsumen->email }}</p>
    <p><strong>Status:</strong> {{ $konsumen->status }}</p>

    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
