@php
    use App\Services\CartService;
    $cartCount = app(CartService::class)->getItemCount();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LELE Pak Dedi - E-Commerce Ikan Lele Segar & Berkualitas')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-lpd sticky-top no-print">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-lpd-primary" href="{{ route('home') }}">
                <span class="bg-lpd-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-water fs-5"></i>
                </span>
                <span>LELE <span class="text-lpd-secondary">Pak Dedi</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-grid me-1"></i> Katalog Produk
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
                                    <i class="bi bi-receipt me-1"></i> Pesanan Saya
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <form action="{{ route('products.index') }}" method="GET" class="d-flex me-lg-3 my-2 my-lg-0">
                    <div class="input-group input-group-sm">
                        <input type="search" name="q" class="form-control" placeholder="Cari lele segar, bibit..." value="{{ request('q') }}">
                        <button class="btn btn-lpd-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-lpd-primary position-relative">
                        <i class="bi bi-cart3"></i>
                        <span class="d-none d-sm-inline ms-1">Keranjang</span>
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-lpd-secondary">Daftar</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle text-lpd-primary"></i>
                                <span class="d-none d-md-inline">{{ Str::limit(auth()->user()->name, 16) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item fw-semibold text-lpd-primary" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">
                                            <i class="bi bi-person me-2"></i> Profil Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customer.addresses.index') }}">
                                            <i class="bi bi-geo-alt me-2"></i> Alamat Pengiriman
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customer.orders.index') }}">
                                            <i class="bi bi-bag-check me-2"></i> Riwayat Pesanan
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- Toast Notifications --}}
    <div class="toast-container position-fixed top-0 end-0 p-3 no-print" style="z-index: 1090;">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="toast align-items-center text-bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer-lpd pt-5 pb-4 mt-5 no-print">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold text-white mb-3">
                        <i class="bi bi-water me-2"></i>LELE Pak Dedi
                    </h5>
                    <p class="small text-white-50">
                        Pusat jual beli ikan lele konsumsi segar, lele hidup berkualitas, bibit lele unggul Sangkuriang & Mutiara, serta lele ukuran jumbo langsung dari kolam budidaya kami.
                    </p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Katalog Produk</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white-50 text-decoration-none">Keranjang Belanja</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Masuk / Daftar</a></li>
                        @endguest
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                        <li><i class="bi bi-geo-alt me-2 text-white"></i>{{ \App\Models\Setting::getValue('store_address', 'Jl. Raya Tambun Selatan No. 88, Bekasi') }}</li>
                        <li><i class="bi bi-whatsapp me-2 text-white"></i>{{ \App\Models\Setting::getValue('store_phone', '0812-3456-7890') }}</li>
                        <li><i class="bi bi-envelope me-2 text-white"></i>{{ \App\Models\Setting::getValue('store_email', 'admin@lelepakdedi.com') }}</li>
                    </ul>
                </div>
            </div>
            <hr class="border-light opacity-25 my-4">
            <div class="text-center small text-white-50">
                &copy; {{ date('Y') }} LELE Pak Dedi. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
