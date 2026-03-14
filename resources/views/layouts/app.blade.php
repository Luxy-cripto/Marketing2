<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Font -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        body, html {
            height: 100%;
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #4A90E2, #9013FE);
        }

        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(6px);
        }

        main {
            min-height: calc(100vh - 56px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

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
</head>

<body>

<div id="app">

    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Left -->
                <ul class="navbar-nav me-auto"></ul>

                <!-- Right -->
                <ul class="navbar-nav ms-auto">

                    @guest

                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    Register
                                </a>
                            </li>
                        @endif

                    @else

                        <li class="nav-item dropdown">

                            <a id="navbarDropdown"
                               class="nav-link dropdown-toggle"
                               href="#"
                               role="button"
                               data-bs-toggle="dropdown">

                                {{ Auth::user()->name }}

                            </a>

                            <div class="dropdown-menu dropdown-menu-end">

                                <a class="dropdown-item"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">

                                    Logout

                                </a>

                                <form id="logout-form"
                                      action="{{ route('logout') }}"
                                      method="POST"
                                      class="d-none">

                                    @csrf

                                </form>

                            </div>

                        </li>

                    @endguest

                </ul>

            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
