@extends('layouts.app')

@section('content')
<div class="login-wrapper">

    <div class="login-box">

        <!-- LEFT -->
        <div class="left-panel">
            <div class="left-content">
                <img src="{{ asset('assets/img/kaiadmin/logo.png') }}" class="logo">

                <h3>Join With Us 🚀</h3>
                <p>
                    Create your account and start<br>
                    your journey with us today
                </p>

                <a href="{{ route('login') }}" class="signup-link">
                    Already have account? Login
                </a>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="right-panel">

            <div class="form-box">

                <div class="text-center mb-4">
                    <div class="icon-user">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h5>REGISTER</h5>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <input type="text" name="name"
                        placeholder="Full Name"
                        class="form-control mb-3" required>

                    <input type="email" name="email"
                        placeholder="Email"
                        class="form-control mb-3" required>

                    <input type="password" name="password"
                        placeholder="Password"
                        class="form-control mb-3" required>

                    <input type="password" name="password_confirmation"
                        placeholder="Confirm Password"
                        class="form-control mb-3" required>

                    <button class="btn-login">Register</button>

                </form>

            </div>

        </div>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

/* BACKGROUND */
.login-wrapper{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* BOX */
.login-box{
    width:850px;
    height:420px;
    background:white;
    border-radius:10px;
    display:flex;
    overflow:hidden;
    box-shadow:0 20px 50px rgba(0,0,0,0.3);

    animation:fadeSlide 0.8s ease;
}

/* LEFT */
.left-panel{
    width:50%;
    background: linear-gradient(135deg, #4ea0ff, #8fa8c9);
    color:white;

    clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%);

    display:flex;
    align-items:center;

    animation:slideLeft 1s ease;
}

/* CONTENT */
.left-content{
    padding:40px;
}

/* LOGO */
.logo{
    width:60px;
    margin-bottom:20px;

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

/* RIGHT */
.right-panel{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:white;

    animation:fadeIn 1.2s ease;
}

/* FORM */
.form-box{
    width:70%;
}

/* INPUT */
.form-control{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:5px;
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
    padding:10px;
    border:none;
    border-radius:5px;
    background:#5f9cff;
    color:white;
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
    margin-bottom:10px;
    color:#5f9cff;

    animation:fadeIn 1.5s ease;
}

/* ================= */
/* ANIMATIONS */
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
