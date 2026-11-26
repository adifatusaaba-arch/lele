@props(['product'])

<div class="card product-card h-100">
    <a href="{{ route('products.show', $product->slug) }}" class="product-card-img-wrapper d-block text-decoration-none">
        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
        @if($product->is_featured)
            <span class="badge bg-lpd-secondary position-absolute top-0 start-0 m-2">
                <i class="bi bi-star-fill me-1"></i>Unggulan
            </span>
        @endif
        @if($product->stock <= 0)
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Stok Habis</span>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <div class="mb-1">
            <span class="badge bg-success-subtle text-lpd-primary rounded-pill small">
                {{ $product->category?->name ?? 'Lele' }}
            </span>
        </div>
        <h6 class="card-title fw-bold mb-1">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark stretched-link-title">
                {{ $product->name }}
            </a>
        </h6>
        @if($product->fish_size)
            <p class="small text-muted mb-2">
                <i class="bi bi-rulers me-1"></i>Ukuran: {{ $product->fish_size }}
            </p>
        @endif
        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold text-lpd-primary fs-6">
                    {{ $product->formatted_price }}
                    <span class="text-muted fw-normal small">/ {{ $product->unit }}</span>
                </div>
                <div class="small text-muted">Stok: {{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}</div>
            </div>
        </div>
        <div class="mt-3 d-grid gap-2">
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="{{ $product->min_order }}">
                    <button type="submit" class="btn btn-lpd-secondary btn-sm w-100">
                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                    </button>
                </form>
            @else
                <button type="button" class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
            @endif
        </div>
    </div>
</div>
