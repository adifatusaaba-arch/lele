@extends('layouts.app')

@section('title', 'Keranjang Belanja - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-lpd-primary mb-0">Keranjang Belanja</h2>
            <p class="text-muted small mb-0">Periksa kembali pesanan ikan lele Anda sebelum checkout</p>
        </div>
        @if($cart && $cart->items->isNotEmpty())
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Kosongkan Keranjang
                </button>
            </form>
        @endif
    </div>

    @if(! $cart || $cart->items->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
            <h5 class="fw-bold">Keranjang Belanja Masih Kosong</h5>
            <p class="text-muted small mb-4">Yuk pilih ikan lele segar, lele hidup, atau bibit lele unggulan dari kolam Pak Dedi.</p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-lpd-primary px-4">
                    <i class="bi bi-grid me-1"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-4">
                        @foreach($cart->items as $item)
                            @php($product = $item->product)
                            <div class="row g-3 align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="col-3 col-md-2">
                                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="img-fluid rounded-3 border">
                                </div>
                                <div class="col-9 col-md-4">
                                    <span class="badge bg-success-subtle text-lpd-primary small mb-1">{{ $product->category?->name }}</span>
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <div class="small text-muted">{{ $product->formatted_price }} / {{ $product->unit }}</div>
                                </div>
                                <div class="col-7 col-md-3">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="{{ $product->min_order }}" max="{{ $product->stock }}" style="width: 75px;">
                                        <button type="submit" class="btn btn-sm btn-outline-lpd-primary" title="Update">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </form>
                                    <small class="text-muted">Min: {{ $product->min_order }} {{ $product->unit }}</small>
                                </div>
                                <div class="col-5 col-md-3 text-end">
                                    <div class="fw-bold text-lpd-primary mb-1">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0 small">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-lpd-primary mb-3">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Item</span>
                            <span class="fw-semibold">{{ $cart->total_quantity }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold fs-5 text-lpd-primary">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @auth
                            <a href="{{ route('customer.checkout.index') }}" class="btn btn-lpd-secondary btn-lg w-100">
                                <i class="bi bi-bag-check me-2"></i>Lanjut ke Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-lpd-primary btn-lg w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk untuk Checkout
                            </a>
                            <p class="small text-muted text-center mt-2 mb-0">Keranjang Anda akan tetap tersimpan setelah masuk.</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
