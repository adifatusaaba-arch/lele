@extends('layouts.admin')

@section('title', 'Pengiriman - Admin LELE Pak Dedi')
@section('page_title', 'Manajemen Pengiriman')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0">Daftar Pengiriman</h6>
        <form action="{{ route('admin.shippings.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="disiapkan" {{ request('status') == 'disiapkan' ? 'selected' : '' }}>Disiapkan</option>
                <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
            </select>
            <button type="submit" class="btn btn-sm btn-lpd-primary">Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Penerima</th>
                    <th>Metode</th>
                    <th>Kurir &amp; Resi</th>
                    <th>Status</th>
                    <th class="text-end">Update Pengiriman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shippings as $shipping)
                    <tr>
                        <td class="fw-semibold">
                            <a href="{{ route('admin.orders.show', $shipping->order) }}" class="text-decoration-none text-lpd-primary">
                                {{ $shipping->order?->order_number }}
                            </a>
                        </td>
                        <td class="small">
                            <div class="fw-semibold">{{ $shipping->order?->shipping_recipient }}</div>
                            <div class="text-muted">{{ $shipping->order?->shipping_city }}</div>
                        </td>
                        <td class="small">{{ $shipping->method_label }}</td>
                        <td class="small">
                            <div>{{ $shipping->courier_name ?? '-' }}</div>
                            <div class="text-muted">{{ $shipping->tracking_number ?? '-' }}</div>
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $shipping->status_label }}</span></td>
                        <td class="text-end">
                            <form action="{{ route('admin.shippings.update', $shipping) }}" method="POST" class="d-flex gap-1 justify-content-end flex-wrap">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="tracking_number" class="form-control form-control-sm" style="width: 130px;" placeholder="No. Resi" value="{{ $shipping->tracking_number }}">
                                <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                    <option value="pending" {{ $shipping->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="disiapkan" {{ $shipping->status == 'disiapkan' ? 'selected' : '' }}>Disiapkan</option>
                                    <option value="dikirim" {{ $shipping->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="diterima" {{ $shipping->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-lpd-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data pengiriman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($shippings->hasPages())
        <div class="card-footer bg-white border-0">{{ $shippings->links() }}</div>
    @endif
</div>
@endsection
