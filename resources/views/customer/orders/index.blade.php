@extends('layouts.app')

@section('title', 'Riwayat Pesanan - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-lpd-primary mb-0">Riwayat Pesanan</h2>
            <p class="text-muted small mb-0">Pantau status pesanan dan pembayaran ikan lele Anda</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-lpd-secondary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Pesan Lagi
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-lpd-primary">{{ $order->order_number }}</td>
                            <td class="small text-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="small">{{ $order->items->count() }} produk</td>
                            <td class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-lpd-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-bag fs-2 d-block mb-2"></i>
                                Anda belum memiliki pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="card-footer bg-white border-0">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
