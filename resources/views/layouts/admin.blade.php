<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Marketing</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: "Segoe UI", sans-serif;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #343a40, #212529);
            color: #fff;
            transition: all 0.3s;
        }

        .sidebar h4 {
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .user-info {
            padding: 20px 10px;
            text-align: center;
            border-bottom: 1px solid #495057;
            margin-bottom: 20px;
        }

        .sidebar .user-info small {
            color: #ced4da;
        }

        .sidebar .nav-link {
            color: #ced4da;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.2s;
            padding: 10px 15px;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        .sidebar hr {
            border-top: 1px solid #495057;
        }

        /* Content */
        .content {
            flex-grow: 1;
            padding: 40px 30px;
            background-color: #f8f9fa;
        }

        /* Card dashboard */
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .card h2 {
            font-weight: 700;
        }

        .card h5 {
            color: #6c757d;
        }

        /* Footer */
        footer {
            background-color: #343a40;
            color: #ced4da;
            padding: 15px 0;
        }

        footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.2s;
        }

        footer a:hover {
            color: #fff;
        }

        footer img {
            height: 28px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">

    <!-- Sidebar -->
    @include('layouts.partial.sidebar')

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>
</div>

<!-- Footer -->
@include('layouts.partial.footer')
<!-- End Footer -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
