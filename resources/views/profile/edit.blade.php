@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <div class="card shadow-lg border-0" style="border-radius:20px;">
        <div class="card-body">

            <h4 class="fw-bold mb-4">Edit Profile</h4>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name"
                        class="form-control"
                        value="{{ Auth::user()->name }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email"
                        class="form-control"
                        value="{{ Auth::user()->email }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('profile') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button class="btn btn-dark">
                        💾 Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
