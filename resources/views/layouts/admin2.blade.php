<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>SIM Marketing</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>

<link rel="icon" href="{{ asset('assets/img/kaiadmin/faviconicom.ico') }}" type="image/x-icon"/>

<!-- Fonts -->
<script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>

<script>
WebFont.load({
    google: { families: ["Public Sans:300,400,500,600,700"] },
    custom: {
        families: [
        "Font Awesome 5 Solid",
        "Font Awesome 5 Regular",
        "Font Awesome 5 Brands",
        "simple-line-icons"
        ],
        urls: ["{{ asset('assets/css/fonts.min.css') }}"],
    },
    active: function () {
        sessionStorage.fonts = true;
    },
});
</script>
<!-- CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

</head>

<body>

<div class="wrapper">

    <!-- Sidebar -->
    @include('layouts.components.sidebar')

    <div class="main-panel">

        <!-- Navbar -->
        @include('layouts.components.navbar')

        <!-- Content -->
        <div class="container">
            <div class="page-inner">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        @include('layouts.components.footer')

    </div>

</div>

<!-- JS -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

</body>
</html>
