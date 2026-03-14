@extends('layouts.app')

@section('content')

<div class="container-fluid d-flex justify-content-center align-items-center">

    <div class="card p-4 shadow-lg"
         style="max-width:420px;width:90%;border-radius:20px;animation:fadeIn 0.8s;">

        <!-- Title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold">Reset Password</h3>
            <p class="text-muted small">
                Please enter your new password
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="input-group mb-3">

                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-envelope"></i>
                </span>

                <input id="email"
                       type="email"
                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ $email ?? old('email') }}"
                       required
                       autocomplete="email"
                       autofocus
                       placeholder="Email Address">

                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

            <!-- Password -->
            <div class="input-group mb-3">

                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-lock"></i>
                </span>

                <input id="password"
                       type="password"
                       class="form-control border-start-0 @error('password') is-invalid @enderror"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="New Password">

                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

            <!-- Confirm Password -->
            <div class="input-group mb-3">

                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-lock"></i>
                </span>

                <input id="password-confirm"
                       type="password"
                       class="form-control border-start-0"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Confirm Password">

            </div>

            <!-- Button -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-gradient btn-lg fw-bold">
                    Reset Password
                </button>
            </div>

        </form>

    </div>

</div>

<style>

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

</style>

@endsection
