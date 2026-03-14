<nav class="sidebar d-flex flex-column p-3">
        <h4 class="text-center mb-4">SIM Marketing</h4>

        <div class="user-info">
            <div><strong>Halo,</strong></div>
            <div>{{ auth()->user()->name ?? 'Guest' }}</div>
            <small>{{ ucfirst(auth()->user()->role ?? '') }}</small>
        </div>

        <hr>

        <div class="nav flex-column">
            <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('marketing.dashboard') }}"
               class="nav-link mb-1 {{ request()->is('admin/dashboard') || request()->is('marketing/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
               <label class="mt-3">Konsumen</label>
            <a href="{{ route('konsumen.index') }}"
               class="nav-link mb-1 {{ request()->is('konsumen*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Konsumen
            </a>
               <label class="mt-3">Follow-Ups</label>
            <div class="nav flex-column">
                <a href="{{ route('followups.index') }}"
                   class="nav-link mb-1 {{ request()->is('followups*') ? 'active' : '' }}">
                   <i class="bi bi-calendar-check me-2"></i> Follow-Ups
                </a>
            </div>
            <label class="mt-3">Transaksi</label>
                <a href="{{ route('transaksi.index') }}"
                   class="nav-link mb-1 {{ request()->is('transaksi*') ? 'active' : '' }}">
                   <i class="bi bi-receipt me-2"></i> Transaksi
                </a>

            @if(auth()->user()->role == 'admin')
            <label class="mt-3">Marketing</label>
            <div class="nav flex-column">
                <a href="{{ route('marketing.dashboard') }}"
                   class="nav-link mb-1 {{ request()->is('marketing*') ? 'active' : '' }}">
                   <i class="bi bi-person-badge me-2"></i> Marketing
                </a>
                <label class="mt-3">Produk</label>
                <a href="{{ route('produk.index') }}"
                   class="nav-link mb-1 {{ request()->is('produk*') ? 'active' : '' }}">
                   <i class="bi bi-box-seam me-2"></i> Produk
                </a>
                <label class="mt-3">Target & Budget</label>
                <a href="{{ route('targets.index') }}"
                   class="nav-link mb-1 {{ request()->is('targets*') ? 'active' : '' }}">
                   <i class="bi bi-graph-up me-2"></i> Target & Budget
                </a>
            </div>
            @endif
        </div>

        @if(auth()->user()->role == 'admin')
            <hr>
            <label class="mt-3">Manajemen User</label>
            <div class="nav flex-column">
                <a href="{{ route('users.index') }}"
                   class="nav-link mb-1 {{ request()->is('users*') ? 'active' : '' }}">
                   <i class="bi bi-people me-2"></i> Manajemen User
                </a>
            </div>
        @endif

        <hr class="mt-auto">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
        </form>
    </nav>
