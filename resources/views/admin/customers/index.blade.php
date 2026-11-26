@extends('layouts.admin')

@section('title', 'Pelanggan - Admin LELE Pak Dedi')
@section('page_title', 'Manajemen Pelanggan')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0">Daftar Pelanggan</h6>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama, email, HP..." value="{{ request('q') }}">
            <button type="submit" class="btn btn-sm btn-lpd-primary">Cari</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Total Pesanan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="fw-semibold">{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $customer->orders_count }} transaksi</span></td>
                        <td>
                            @if($customer->is_active)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-lpd-primary">Detail</a>
                            <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-{{ $customer->is_active ? 'warning' : 'success' }}">
                                    {{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
        <div class="card-footer bg-white border-0">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
