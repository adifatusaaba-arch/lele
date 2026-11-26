@extends('layouts.app')

@section('title', 'LELE Pak Dedi - Jual Beli Ikan Lele Segar, Hidup & Bibit Unggul')

@section('content')
<div class="container py-4">
    {{-- Hero Section --}}
    <section class="hero-section p-4 p-lg-5 mb-5 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-white text-lpd-primary fw-semibold px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-award-fill me-1 text-lpd-secondary"></i> Langsung dari Kolam Budidaya Pak Dedi
                </span>
                <h1 class="display-5 fw-bold mb-3">
                    Lele Segar, Hidup &amp; Bibit Unggul Berkualitas
                </h1>
                <p class="lead text-white-50 mb-4">
                    Melayani kebutuhan rumah tangga, warung pecel lele, restoran, hingga peternak budidaya. Dijamin segar, tidak bau tanah, dan pengemasan aman beroksigen.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg fw-bold text-lpd-primary px-4">
                        <i class="bi bi-bag-fill me-2"></i>Belanja Sekarang
                    </a>
                    <a href="{{ route('products.index', ['category' => 'bibit-lele']) }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-stars me-2"></i>Lihat Bibit Lele
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <img src="{{ asset('images/hero-lele.svg') }}" alt="Ikan Lele Segar Pak Dedi" class="img-fluid rounded-4 shadow">
            </div>
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="mb-5">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center">
                    <i class="bi bi-droplet-fill fs-2 text-lpd-secondary mb-2"></i>
                    <h6 class="fw-bold mb-1">Kolam Air Bersih</h6>
                    <small class="text-muted">Bebas bau lumpur, pakan pelet berkualitas</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center">
                    <i class="bi bi-heart-pulse-fill fs-2 text-lpd-secondary mb-2"></i>
                    <h6 class="fw-bold mb-1">Garansi Ikan Hidup</h6>
                    <small class="text-muted">Kemasan oksigen murni untuk pengiriman jauh</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center">
                    <i class="bi bi-tags-fill fs-2 text-lpd-secondary mb-2"></i>
                    <h6 class="fw-bold mb-1">Harga Kolam Langsung</h6>
                    <small class="text-muted">Lebih hemat untuk eceran maupun grosir warung</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center">
                    <i class="bi bi-truck fs-2 text-lpd-secondary mb-2"></i>
                    <h6 class="fw-bold mb-1">Pengiriman Cepat</h6>
                    <small class="text-muted">Kurir kolam same-day atau ambil di tempat</small>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori Produk --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-lpd-primary mb-0">Kategori Pilihan</h3>
                <p class="text-muted small mb-0">Pilih jenis lele sesuai kebutuhan Anda</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-lpd-primary">Semua Produk</a>
        </div>
        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-6 col-lg-3">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="category-card p-3 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="bg-success-subtle text-lpd-primary rounded-3 p-2">
                                        <i class="bi bi-water fs-4"></i>
                                    </span>
                                    <span class="badge bg-light text-dark border">{{ $category->products_count }} produk</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ $category->name }}</h6>
                                <p class="small text-muted mb-0">{{ Str::limit($category->description, 70) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Produk Unggulan --}}
    @if($featuredProducts->isNotEmpty())
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-lpd-primary mb-0">Produk Unggulan</h3>
                <p class="text-muted small mb-0">Paling banyak dipesan pelanggan kami</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-lpd-primary">Lihat Katalog</a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-sm-6 col-lg-3">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Produk Terbaru --}}
    <section class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-lpd-primary mb-0">Katalog Terbaru</h3>
                <p class="text-muted small mb-0">Stok segar siap kirim hari ini</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-lpd-primary">Jelajahi Semua</a>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
                <div class="col-sm-6 col-lg-3">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
