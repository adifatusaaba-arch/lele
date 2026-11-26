@extends('layouts.admin')

@section('title', 'Produk - Admin LELE Pak Dedi')
@section('page_title', 'Manajemen Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <h6 class="fw-bold mb-0">Daftar Produk Lele</h6>
            </div>
            <div class="col-md-8">
                <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <input type="text" name="q" class="form-control form-control-sm" style="max-width: 200px;" placeholder="Cari produk..." value="{{ request('q') }}">
                    <select name="category_id" class="form-select form-select-sm" style="max-width: 180px;">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-lpd-primary">Filter</button>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-lpd-primary">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Ukuran</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="rounded-3 border" style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if($product->is_featured)
                                        <span class="badge bg-warning-subtle text-dark small">Unggulan</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category?->name }}</td>
                        <td class="fw-semibold">{{ $product->formatted_price }} / {{ $product->unit }}</td>
                        <td class="small text-muted">{{ $product->fish_size ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $product->stock <= 20 ? 'bg-danger-subtle text-danger' : 'bg-light text-dark border' }}">
                                {{ number_format($product->stock) }} {{ $product->unit }}
                            </span>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="card-footer bg-white border-0">{{ $products->links() }}</div>
    @endif
</div>
@endsection
