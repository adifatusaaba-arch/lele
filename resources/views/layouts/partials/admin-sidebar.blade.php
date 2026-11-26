<div class="p-3 border-bottom border-light border-opacity-25">
    <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
        <span class="bg-lpd-secondary rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="bi bi-water fs-5"></i>
        </span>
        <div>
            <div class="fw-bold lh-1">LELE Pak Dedi</div>
            <small class="text-white-50">Admin Panel</small>
        </div>
    </a>
</div>

<nav class="nav flex-column py-3 flex-grow-1">
    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
        <i class="bi bi-tags"></i> Kategori
    </a>
    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
        <i class="bi bi-box-seam"></i> Produk
    </a>
    <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
        <i class="bi bi-people"></i> Pelanggan
    </a>
    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
        <i class="bi bi-receipt"></i> Pesanan
    </a>
    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
        <i class="bi bi-credit-card"></i> Pembayaran
    </a>
    <a class="nav-link {{ request()->routeIs('admin.shippings.*') ? 'active' : '' }}" href="{{ route('admin.shippings.index') }}">
        <i class="bi bi-truck"></i> Pengiriman
    </a>
    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
        <i class="bi bi-graph-up-arrow"></i> Laporan
    </a>
    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
        <i class="bi bi-gear"></i> Pengaturan
    </a>

    <hr class="border-light opacity-25 mx-3 my-2">

    <form action="{{ route('logout') }}" method="POST" class="px-2">
        @csrf
        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-warning">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</nav>
