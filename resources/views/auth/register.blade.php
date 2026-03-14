@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center"
     style="background: linear-gradient(135deg, #4A90E2, #9013FE);">

    <div class="card p-4 shadow-lg"
         style="border-radius: 20px; max-width: 400px; width: 90%; background: rgba(255,255,255,0.95); animation: fadeIn 0.8s;">

        <!-- Logo & Title -->
        <div class="text-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 100px; margin-bottom: 10px;">
            <h3 class="fw-bold">Register</h3>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-user"></i>
                </span>
                <input id="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                       placeholder="Full Name">
                @error('name')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Email -->
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-envelope"></i>
                </span>
                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email"
                       placeholder="Email Address">
                @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Password -->
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-lock"></i>
                </span>
                <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password"
                       placeholder="Password">
                @error('password')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="input-group mb-4">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-lock"></i>
                </span>
                <input id="password-confirm" type="password" class="form-control border-start-0"
                       name="password_confirmation" required placeholder="Confirm Password">
            </div>

            <!-- Submit Button -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-gradient btn-lg fw-bold">
                    Register
                </button>
            </div>

            <!-- Already have account -->
            <div class="text-center">
                <button type="button" class="btn btn-link" onclick="window.location.href='{{ route('login') }}'">
                    Already have an account? Login
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Fade-in animation for the card */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Gradient button */
.btn-gradient {
    border-radius: 50px;
    background: linear-gradient(90deg, #4A90E2, #9013FE);
    color: white;
    transition: 0.3s;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
</style>
@endsection
