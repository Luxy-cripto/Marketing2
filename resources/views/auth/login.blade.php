@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center"
     style="background: linear-gradient(135deg,#4A90E2,#9013FE);">

    <div class="card p-4 shadow-lg"
         style="border-radius: 20px; max-width: 400px; width: 90%; background: rgba(255,255,255,0.95); animation: fadeIn 0.8s;">

        <!-- Logo -->
        <div class="text-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 100px; margin-bottom: 10px;">
            <h3 class="fw-bold">Login</h3>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-envelope"></i>
                </span>
                <input id="email" type="email"
                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}"
                       required autocomplete="email" autofocus
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
                <input id="password" type="password"
                       class="form-control border-start-0 @error('password') is-invalid @enderror"
                       name="password"
                       required autocomplete="current-password"
                       placeholder="Password">

                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Remember -->
            <div class="form-check mb-3">
                <input class="form-check-input"
                       type="checkbox"
                       name="remember"
                       id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <!-- Login Button -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-gradient btn-lg fw-bold">
                    Login
                </button>
            </div>

            <!-- Register -->
            <div class="text-center">
                <button type="button" class="btn btn-link"
                        onclick="window.location.href='{{ route('register') }}'">
                    Don't have an account? Register
                </button>
            </div>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
            <div class="text-center">
                <a class="text-decoration-none"
                   href="{{ route('password.request') }}"
                   style="color:#4A90E2;">
                   Forgot Your Password?
                </a>
            </div>
            @endif

        </form>
    </div>
</div>

<style>

@keyframes fadeIn {
    from {
        opacity:0;
        transform: translateY(-20px);
    }
    to {
        opacity:1;
        transform: translateY(0);
    }
}

.btn-gradient{
    border-radius:50px;
    background: linear-gradient(90deg,#4A90E2,#9013FE);
    color:white;
    transition:0.3s;
}

.btn-gradient:hover{
    transform: translateY(-2px);
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

</style>
@endsection
