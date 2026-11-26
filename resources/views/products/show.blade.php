@extends('layouts.app')

@section('title', $product->name . ' - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-lpd-primary">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category?->slug]) }}" class="text-decoration-none text-lpd-primary">{{ $product->category?->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="rounded-4 overflow-hidden border bg-light mb-3" style="height: 360px;">
                    <img id="mainProductImg" src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                </div>
                @if($product->images->count() > 1)
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($product->images as $img)
                            <img src="{{ $img->url }}" alt="Thumb" class="rounded-3 border cursor-pointer" style="width: 72px; height: 72px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('mainProductImg').src=this.src">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-7">
                <span class="badge bg-success-subtle text-lpd-primary rounded-pill mb-2 px-3 py-2">
                    {{ $product->category?->name }}
                </span>
                <h2 class="fw-bold mb-2">{{ $product->name }}</h2>

                <div class="fs-2 fw-bold text-lpd-primary mb-3">
                    {{ $product->formatted_price }}
                    <span class="fs-6 fw-normal text-muted">/ {{ $product->unit }}</span>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-2 bg-light">
                            <small class="text-muted d-block">Ukuran Ikan</small>
                            <span class="fw-semibold">{{ $product->fish_size ?? 'Standar' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-2 bg-light">
                            <small class="text-muted d-block">Minimal Order</small>
                            <span class="fw-semibold">{{ $product->min_order }} {{ $product->unit }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-2 bg-light">
                            <small class="text-muted d-block">Stok Tersedia</small>
                            <span class="fw-semibold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Deskripsi Produk</h6>
                    <p class="text-muted mb-0" style="white-space: pre-line;">{{ $product->description ?: 'Ikan lele segar berkualitas langsung dari kolam Pak Dedi.' }}</p>
                </div>

                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="row g-3 align-items-center">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="col-auto">
                            <label class="form-label small fw-semibold mb-1">Jumlah ({{ $product->unit }})</label>
                            <div class="input-group" style="width: 150px;">
                                <button class="btn btn-outline-secondary" type="button" data-qty-btn="minus" data-target="productQty">-</button>
                                <input type="number" name="quantity" id="productQty" class="form-control text-center fw-bold" value="{{ $product->min_order }}" min="{{ $product->min_order }}" max="{{ $product->stock }}" required>
                                <button class="btn btn-outline-secondary" type="button" data-qty-btn="plus" data-target="productQty">+</button>
                            </div>
                        </div>
                        <div class="col-sm">
                            <label class="form-label d-none d-sm-block mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-lpd-secondary btn-lg w-100">
                                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                @else
                    <button type="button" class="btn btn-secondary btn-lg w-100" disabled>Stok Sedang Habis</button>
                @endif
            </div>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <section>
            <h4 class="fw-bold text-lpd-primary mb-3">Produk Terkait</h4>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-sm-6 col-lg-3">
                        <x-product-card :product="$related" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
