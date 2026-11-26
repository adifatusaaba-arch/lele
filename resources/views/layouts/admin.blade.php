<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - LELE Pak Dedi')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        {{-- Desktop Sidebar --}}
        <aside class="admin-sidebar d-none d-lg-flex no-print">
            @include('layouts.partials.admin-sidebar')
        </aside>

        {{-- Mobile Offcanvas Sidebar --}}
        <div class="offcanvas offcanvas-start bg-lpd-primary text-white d-lg-none no-print" tabindex="-1" id="adminMobileSidebar" style="width: 260px;">
            <div class="offcanvas-header border-bottom border-light border-opacity-25">
                <h5 class="offcanvas-title fw-bold">LELE Pak Dedi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                @include('layouts.partials.admin-sidebar')
            </div>
        </div>

        {{-- Main Area --}}
        <div class="admin-main">
            <header class="admin-topbar d-flex align-items-center justify-content-between no-print">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h5 class="mb-0 fw-bold text-lpd-primary">@yield('page_title', 'Dashboard')</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-lpd-primary">
                        <i class="bi bi-shop me-1"></i> Lihat Toko
                    </a>
                    <span class="small text-muted d-none sm:inline d-md-inline">
                        <i class="bi bi-person-badge me-1 text-lpd-primary"></i>{{ auth()->user()->name }}
                    </span>
                </div>
            </header>

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

            <main class="p-3 p-md-4 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
