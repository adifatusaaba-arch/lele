@extends('layouts.admin')

@section('title', 'Edit Produk - Admin LELE Pak Dedi')
@section('page_title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', (int) $product->price) }}" min="0" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select" required>
                        <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="ekor" {{ old('unit', $product->unit) == 'ekor' ? 'selected' : '' }}>Ekor</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ukuran Ikan</label>
                    <input type="text" name="fish_size" class="form-control" value="{{ old('fish_size', $product->fish_size) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Minimal Order</label>
                    <input type="number" name="min_order" class="form-control" value="{{ old('min_order', $product->min_order) }}" min="1" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tambah Foto Baru</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="col-12 d-flex gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $product->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Produk Aktif</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ $product->is_featured ? 'checked' : '' }}>
                        <label class="form-check-label" for="isFeatured">Tampilkan di Unggulan</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-lpd-primary"><i class="bi bi-save me-1"></i> Perbarui Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light border">Kembali</a>
            </div>
        </form>

        @if($product->images->isNotEmpty())
            <hr class="my-4">
            <h6 class="fw-bold mb-3">Foto Produk Saat Ini</h6>
            <div class="d-flex flex-wrap gap-3">
                @foreach($product->images as $img)
                    <div class="position-relative border rounded-3 p-1 bg-light" style="width: 120px;">
                        <img src="{{ $img->url }}" alt="Foto" class="rounded-2 w-100" style="height: 90px; object-fit: cover;">
                        @if($img->is_primary)
                            <span class="badge bg-lpd-primary position-absolute top-0 start-0 m-1">Utama</span>
                        @endif
                        <form action="{{ route('admin.products.images.destroy', [$product, $img]) }}" method="POST" class="mt-1" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-0">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
