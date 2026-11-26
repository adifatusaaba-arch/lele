@extends('layouts.admin')

@section('title', 'Tambah Produk - Admin LELE Pak Dedi')
@section('page_title', 'Tambah Produk Baru')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Satuan Penjualan <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="ekor" {{ old('unit') == 'ekor' ? 'selected' : '' }}>Ekor</option>
                    </select>
                    @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ukuran Ikan</label>
                    <input type="text" name="fish_size" class="form-control" value="{{ old('fish_size') }}" placeholder="mis. 8-10 ekor/kg atau 5-7 cm">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Minimal Order <span class="text-danger">*</span></label>
                    <input type="number" name="min_order" class="form-control" value="{{ old('min_order', 1) }}" min="1" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Foto Produk (Bisa pilih beberapa, maks 2MB/file)</label>
                    <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" accept="image/*" multiple>
                    @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi Produk</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="col-12 d-flex gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Produk Aktif</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured">
                        <label class="form-check-label" for="isFeatured">Tampilkan di Unggulan</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-lpd-primary"><i class="bi bi-save me-1"></i> Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
