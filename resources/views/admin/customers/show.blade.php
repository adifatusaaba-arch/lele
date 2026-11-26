@extends('layouts.admin')

@section('title', 'Detail Pelanggan - Admin LELE Pak Dedi')
@section('page_title', 'Detail Pelanggan')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold text-lpd-primary mb-3">Informasi Pelanggan</h6>
                <p class="mb-1"><strong>Nama:</strong> {{ $customer->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $customer->email }}</p>
                <p class="mb-1"><strong>No. HP:</strong> {{ $customer->phone ?? '-' }}</p>
                <p class="mb-3"><strong>Terdaftar:</strong> {{ $customer->created_at->format('d M Y') }}</p>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-light border">Kembali</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body">
                <h6 class="fw-bold text-lpd-primary mb-3">Alamat Tersimpan</h6>
                @forelse($customer->addresses as $addr)
                    <div class="border rounded-3 p-2 mb-2 small">
                        <div class="fw-bold">{{ $addr->label }} @if($addr->is_default)<span class="badge bg-lpd-secondary">Utama</span>@endif</div>
                        <div>{{ $addr->recipient_name }} ({{ $addr->phone }})</div>
                        <div class="text-muted">{{ $addr->formatted_address }}</div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada alamat tersimpan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0">Riwayat Pesanan Pelanggan</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td class="small text-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-lpd-primary">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
