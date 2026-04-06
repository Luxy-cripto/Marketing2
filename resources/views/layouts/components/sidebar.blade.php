<!-- Sidebar -->
<div class="sidebar custom-sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-header">
            <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('marketing.dashboard') }}"
                class="logo">
                <img src="{{ asset('assets/img/kaiadmin/file1.png') }}" class="navbar-brand" height="60" alt="Logo" />
            </a>

            <!-- Toggle Buttons -->
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>

            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">

            <ul class="nav nav-secondary">

                @if (auth()->user()->role == 'admin')
                    <!-- Dashboard -->
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'marketing')
                    <!-- Dashboard Marketing -->
                    <li class="nav-item {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('marketing.dashboard') }}">
                            <i class="fas fa-chart-line"></i>
                            <p>Dashboard Marketing</p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == 'admin')
                    <!-- User -->
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i>
                            <p>Manajemen User</p>
                        </a>
                    </li>

                    <!-- Produk -->
                    <li class="nav-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                        <a href="{{ route('produk.index') }}">
                            <i class="fas fa-box"></i>
                            <p>Produk</p>
                        </a>
                    </li>

                    <!-- Target -->
                    <li class="nav-item {{ request()->routeIs('targets.*') ? 'active' : '' }}">
                        <a href="{{ route('targets.index') }}">
                            <i class="fas fa-bullseye"></i>
                            <p>Target</p>
                        </a>
                    </li>
                @endif

                <!-- Konsumen -->
                <li class="nav-item {{ request()->routeIs('konsumen.*') ? 'active' : '' }}">
                    <a href="{{ route('konsumen.index') }}">
                        <i class="fas fa-user-friends"></i>
                        <p>Data Konsumen</p>
                    </a>
                </li>

                <!-- Transaksi -->
                <li class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                <!-- Follow Up -->
                <li class="nav-item {{ request()->routeIs('followups.*') ? 'active' : '' }}">
                    <a href="{{ route('followups.index') }}">
                        <i class="fas fa-phone"></i>
                        <p>Follow Up</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>

            </ul>

        </div>
    </div>
</div>

<style>
    /* SIDEBAR GRADIENT */
    .custom-sidebar {
        background: linear-gradient(135deg, #ffffff, #385f88);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }

    /* Logo header */
    .custom-sidebar .logo-header {
        background: transparent !important;
    }

    /* Text & icon */
    .custom-sidebar .nav-item a {
        color: #ffffff;
        transition: 0.3s;
    }

    .custom-sidebar .nav-item a i {
        color: #ffffff;
    }

    /* Hover */
    .custom-sidebar .nav-item a:hover {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding-left: 10px;
    }

    /* ACTIVE MENU (LEBIH JELAS) */
    .custom-sidebar .nav-item.active a {
        background: #ffffff;
        color: #5296e4 !important;
        border-radius: 10px;
        font-weight: bold;
    }

    .custom-sidebar .nav-item.active a i {
        color: #5296e4 !important;
    }

    /* Scrollbar */
    .custom-sidebar .scrollbar-inner {
        scrollbar-width: thin;
    }
</style>
