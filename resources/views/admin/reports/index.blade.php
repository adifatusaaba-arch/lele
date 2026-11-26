@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Admin LELE Pak Dedi')
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
    <div class="card-body">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-lpd-primary flex-grow-1"><i class="bi bi-funnel me-1"></i> Tampilkan</button>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card p-3">
            <div class="small text-muted fw-semibold">Total Pendapatan</div>
            <div class="fs-4 fw-bold text-lpd-secondary mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card p-3">
            <div class="small text-muted fw-semibold">Total Transaksi Sukses</div>
            <div class="fs-4 fw-bold text-lpd-primary mt-1">{{ number_format($totalOrders) }} transaksi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card p-3">
            <div class="small text-muted fw-semibold">Rata-rata Nilai Pesanan</div>
            <div class="fs-4 fw-bold text-lpd-primary mt-1">Rp {{ number_format($averageOrder, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0">Produk Terlaris</h6>
            </div>
            <div class="card-body">
                @forelse($topProducts as $item)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-semibold small">{{ $item->product_name }}</div>
                            <small class="text-muted">{{ number_format($item->total_qty) }} {{ $item->unit }} terjual</small>
                        </div>
                        <span class="fw-bold small text-lpd-primary">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada penjualan pada periode ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0">Rincian Transaksi</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->user?->name }}</td>
                                <td class="small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                                <td class="text-end fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td>
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
</div>
@endsection
