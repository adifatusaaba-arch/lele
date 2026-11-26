@extends('layouts.app')

@section('title', 'Katalog Produk - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-lpd-primary mb-1">Katalog Ikan Lele</h2>
            <p class="text-muted small mb-0">Temukan lele konsumsi, lele hidup, bibit lele, dan lele jumbo terbaik</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cari Produk</label>
                    <input type="text" name="q" class="form-control" placeholder="Nama atau ukuran ikan..." value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Satuan</label>
                    <select name="unit" class="form-select">
                        <option value="">Semua</option>
                        <option value="kg" {{ request('unit') == 'kg' ? 'selected' : '' }}>Per Kg</option>
                        <option value="ekor" {{ request('unit') == 'ekor' ? 'selected' : '' }}>Per Ekor</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                    <button type="submit" class="btn btn-lpd-primary px-3"><i class="bi bi-funnel"></i></button>
                    @if(request()->hasAny(['q', 'category', 'unit', 'sort']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <x-product-card :product="$product" />
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h5 class="fw-bold">Produk tidak ditemukan</h5>
                    <p class="text-muted small">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-lpd-primary">Lihat Semua Produk</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
