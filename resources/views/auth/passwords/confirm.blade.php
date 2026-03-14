@extends('layouts.app')

@section('content')

<div class="container-fluid d-flex justify-content-center align-items-center">

    <div class="card p-4 shadow-lg"
        style="max-width:420px;width:90%;border-radius:20px;animation:fadeIn 0.8s;">

        <!-- Title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold">Confirm Password</h3>
            <p class="text-muted small">
                Please confirm your password before continuing
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

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
                       autocomplete="current-password"
                       placeholder="Enter your password">

                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

            <!-- Button -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-gradient btn-lg fw-bold">
                    Confirm Password
                </button>
            </div>

            <!-- Forgot -->
            @if (Route::has('password.request'))
                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        Forgot Your Password?
                    </a>
                </div>
            @endif

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
