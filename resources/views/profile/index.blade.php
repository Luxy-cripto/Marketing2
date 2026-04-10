@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-lg border-0" style="border-radius:20px;">
        <div class="card-body text-center">

            <!-- AVATAR -->
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                 class="rounded-circle mb-3 shadow" width="110">

            <h4 class="fw-bold">{{ Auth::user()->name }}</h4>
            <p class="text-muted">{{ Auth::user()->email }}</p>

            <div class="row mt-4 text-start">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ Auth::user()->id }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Bergabung:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-sm px-4">
                    ✏️ Edit Profile
                </a>
            </div>

        </div>
    </div>

</div>

@endsection
