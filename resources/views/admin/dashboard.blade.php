@extends('layouts.admin')

@section('title', 'Dashboard Admin - LELE Pak Dedi')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small text-muted fw-semibold">Total Produk</div>
                    <div class="fs-3 fw-bold text-lpd-primary mt-1">{{ number_format($totalProducts) }}</div>
                </div>
                <div class="stat-icon bg-success-subtle text-lpd-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small text-muted fw-semibold">Total Pelanggan</div>
                    <div class="fs-3 fw-bold text-lpd-primary mt-1">{{ number_format($totalCustomers) }}</div>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small text-muted fw-semibold">Total Transaksi</div>
                    <div class="fs-3 fw-bold text-lpd-primary mt-1">{{ number_format($totalOrders) }}</div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small text-muted fw-semibold">Total Pendapatan</div>
                    <div class="fs-4 fw-bold text-lpd-secondary mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon bg-success-subtle text-lpd-secondary">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2 text-lpd-secondary"></i>Grafik Penjualan 7 Hari Terakhir</h6>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-lpd-primary">Lihat Laporan</a>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Stok Menipis (&le; 20)</h6>
            </div>
            <div class="card-body">
                @forelse($lowStockProducts as $item)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-semibold small">{{ $item->name }}</div>
                            <small class="text-muted">{{ $item->category?->name }}</small>
                        </div>
                        <span class="badge bg-danger-subtle text-danger">{{ $item->stock }} {{ $item->unit }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Semua stok produk dalam kondisi aman.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-lpd-primary"></i>Pesanan Terbaru</h6>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-lpd-primary">Semua Pesanan</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->order_number }}</td>
                        <td>{{ $order->user?->name ?? $order->shipping_recipient }}</td>
                        <td class="small text-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-lpd-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chartData),
                    backgroundColor: '#16A34A',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
