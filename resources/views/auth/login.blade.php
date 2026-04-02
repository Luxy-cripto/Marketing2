@extends('layouts.app')

@section('content')
<div class="container-login">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="login-box">

        <!-- KIRI (WELCOME PANEL) -->
        <div class="left-panel">
            <div class="left-content">
                <img src="{{ asset('assets/img/kaiadmin/logo.png') }}" class="left-logo">
                <h3>Welcome to our icommits</h3>
                <p>Welcome to sim marketing<br>a place to share your knowledge<br>and experience</p>

                <a href="{{ route('register') }}" class="signup-link">
                    Don't have account? Sign Up
                </a>
            </div>
        </div>

        <!-- KANAN (FORM LOGIN) -->
        <div class="right-panel">

            <div class="form-box">

                <div class="text-center mb-4">
                    <div class="icon-user">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="mt-2">LOGIN</h5>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <input type="email" name="email"
                           placeholder="Email"
                           class="form-control mb-3 @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>

                    @error('email')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <input type="password" name="password"
                           placeholder="Password"
                           class="form-control mb-3 @error('password') is-invalid @enderror"
                           required>

                    @error('password')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <button class="btn-login">Login</button>

                </form>

            </div>

        </div>

    </div>

</div>

<style>

/* BACKGROUND */
.container-login{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background-size:cover;
    background-attachment:fixed;
    background-repeat:no-repeat;
    background-position:center center;
}

/* BOX */
.login-box{
    display:flex;
    width:800px;
    height:450px;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 20px 50px rgba(0,0,0,0.3);

    /* ANIMASI MASUK */
    animation:fadeSlide 0.8s ease;
}

/* LEFT PANEL */
.left-panel{
    width:40%;
    background: linear-gradient(135deg, #4ea0ff, #8fa8c9);
    color:white;
    position:relative;

    clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%);

    display:flex;
    justify-content:center;
    align-items:center;

    /* ANIMASI */
    animation:slideLeft 1s ease;
}

/* TEXT */
.left-content{
    padding:20px;
    text-align:left;
}

/* LOGO */
.left-logo{
    width:70px;
    margin-bottom:15px;

    /* FLOAT EFFECT */
    animation:float 3s ease-in-out infinite;
}

/* LINK */
.signup-link{
    display:inline-block;
    margin-top:20px;
    color:white;
    font-size:13px;
    text-decoration:none;
    transition:0.3s;
}

.signup-link:hover{
    opacity:0.8;
}

/* RIGHT PANEL */
.right-panel{
    width:60%;
    display:flex;
    justify-content:center;
    align-items:center;

    animation:fadeIn 1.2s ease;
}

/* FORM */
.form-box{
    width:80%;
}

/* INPUT */
.form-control{
    height:40px;
    border-radius:5px;
    border:1px solid #ddd;
    padding:10px;
    transition:0.3s;
}

/* FOCUS EFFECT */
.form-control:focus{
    border-color:#4ea0ff;
    box-shadow:0 0 8px rgba(78,160,255,0.4);
    transform:scale(1.02);
}

/* BUTTON */
.btn-login{
    width:100%;
    background:#4ea0ff;
    color:white;
    border:none;
    padding:10px;
    border-radius:5px;
    transition:0.3s;
}

/* HOVER */
.btn-login:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

/* CLICK */
.btn-login:active{
    transform:scale(0.97);
}

/* ICON */
.icon-user{
    width:50px;
    height:50px;
    border-radius:50%;
    background:#eee;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:auto;
    font-size:20px;
    color:#4ea0ff;

    animation:fadeIn 1.5s ease;
}

/* ================= */
/* KEYFRAMES */
/* ================= */

@keyframes fadeSlide{
    from{
        opacity:0;
        transform:translateY(30px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes slideLeft{
    from{
        transform:translateX(-50px);
        opacity:0;
    }
    to{
        transform:translateX(0);
        opacity:1;
    }
}

@keyframes fadeIn{
    from{ opacity:0; }
    to{ opacity:1; }
}

@keyframes float{
    0%,100%{ transform:translateY(0); }
    50%{ transform:translateY(-8px); }
}


</style>
@endsection
