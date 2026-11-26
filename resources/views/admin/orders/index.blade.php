@extends('layouts.admin')

@section('title', 'Pesanan - Admin LELE Pak Dedi')
@section('page_title', 'Manajemen Pesanan')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <h6 class="fw-bold mb-0">Daftar Semua Pesanan</h6>
            </div>
            <div class="col-md-8 d-flex flex-wrap gap-2 justify-content-md-end">
                <input type="text" name="q" class="form-control form-control-sm" style="max-width: 200px;" placeholder="No. pesanan / nama..." value="{{ request('q') }}">
                <select name="status" class="form-select form-select-sm" style="max-width: 190px;">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-lpd-primary">Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold">{{ $order->order_number }}</td>
                        <td>{{ $order->user?->name ?? $order->shipping_recipient }}</td>
                        <td class="small text-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="small">{{ $order->payment?->method_label }}</td>
                        <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-lpd-primary">Kelola</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white border-0">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
