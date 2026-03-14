@extends('layouts.admin2')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-light">
            <h4 class="mb-0 fw-semibold">
                👤 Detail User
            </h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">
                    Nama
                </div>
                <div class="col-md-9">
                    {{ $user->name }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">
                    Email
                </div>
                <div class="col-md-9">
                    {{ $user->email }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">
                    Role
                </div>
                <div class="col-md-9">
                    <span class="badge bg-secondary">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 fw-semibold text-muted">
                    Status
                </div>
                <div class="col-md-9">
                    @if($user->is_active)
                        <span class="badge bg-success">
                            Aktif
                        </span>
                    @else
                        <span class="badge bg-danger">
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            <hr>

            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                ← Kembali
            </a>

        </div>
    </div>

</div>
@endsection
