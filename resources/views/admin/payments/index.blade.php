@extends('layouts.admin')

@section('title', 'Pembayaran - Admin LELE Pak Dedi')
@section('page_title', 'Verifikasi Pembayaran')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0">Daftar Pembayaran</h6>
        <form action="{{ route('admin.payments.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-sm btn-lpd-primary">Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Nominal</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="fw-semibold">
                            <a href="{{ route('admin.orders.show', $payment->order) }}" class="text-decoration-none text-lpd-primary">
                                {{ $payment->order?->order_number }}
                            </a>
                        </td>
                        <td>{{ $payment->order?->user?->name }}</td>
                        <td class="small">{{ $payment->method_label }}</td>
                        <td class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>
                            @if($payment->proof_url)
                                <a href="{{ $payment->proof_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat Bukti</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badge = match($payment->status) {
                                    'verified' => 'success',
                                    'menunggu_verifikasi' => 'info text-dark',
                                    'rejected' => 'danger',
                                    default => 'warning text-dark',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ $payment->status_label }}</span>
                        </td>
                        <td class="text-end">
                            @if($payment->status === 'menunggu_verifikasi')
                                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-lpd-secondary">Verifikasi</button>
                                </form>
                            @else
                                <a href="{{ route('admin.orders.show', $payment->order) }}" class="btn btn-sm btn-outline-lpd-primary">Detail</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="card-footer bg-white border-0">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
