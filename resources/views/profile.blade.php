@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-body text-center">

            <!-- FOTO -->
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                 class="rounded-circle mb-3" width="100">

            <!-- NAMA -->
            <h4 class="fw-bold">{{ Auth::user()->name }}</h4>

            <!-- EMAIL -->
            <p class="text-muted">{{ Auth::user()->email }}</p>

            <!-- INFO TAMBAHAN -->
            <hr>

            <div class="row">
                <div class="col-md-6 text-start">
                    <p><strong>ID User:</strong> {{ Auth::user()->id }}</p>
                </div>
                <div class="col-md-6 text-start">
                    <p><strong>Bergabung:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="mt-3">
                <a href="#" class="btn btn-primary btn-sm">Edit Profile</a>
                <a href="/" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>

        </div>
    </div>

</div>

@endsection
