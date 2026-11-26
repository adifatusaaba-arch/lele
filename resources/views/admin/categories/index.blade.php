@extends('layouts.admin')

@section('title', 'Kategori Produk - Admin LELE Pak Dedi')
@section('page_title', 'Manajemen Kategori')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Daftar Kategori Produk</h6>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-lpd-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Jumlah Produk</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->slug }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $category->products_count }} produk</span></td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                        <td colspan="7" class="text-center text-muted py-4">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="card-footer bg-white border-0">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
