@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number . ' - Admin LELE Pak Dedi')
@section('page_title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-outline-lpd-primary">
        <i class="bi bi-printer me-1"></i> Cetak Invoice
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between">
                <h6 class="fw-bold mb-0">Item Pesanan</h6>
                <span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->product_name }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }} / {{ $item->unit }}</td>
                                <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end">Subtotal</td>
                            <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Ongkos Kirim</td>
                            <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold text-lpd-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold text-lpd-primary mb-3">Alamat &amp; Pengiriman</h6>
                <p class="small mb-1"><strong>Penerima:</strong> {{ $order->shipping_recipient }} ({{ $order->shipping_phone }})</p>
                <p class="small mb-2"><strong>Alamat:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                @if($order->notes)
                    <p class="small text-muted mb-0"><strong>Catatan:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-lpd-primary mb-3">Ubah Status Pesanan</h6>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-lpd-primary w-100">Simpan Status</button>
                </form>
                <small class="text-muted d-block mt-2">
                    Stok dikurangi: <span class="fw-bold">{{ $order->stock_deducted ? 'Ya' : 'Belum' }}</span>
                </small>
            </div>
        </div>

        @if($order->payment)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold text-lpd-primary mb-3">Informasi Pembayaran</h6>
                <p class="small mb-1"><strong>Metode:</strong> {{ $order->payment->method_label }}</p>
                <p class="small mb-1"><strong>Status:</strong> {{ $order->payment->status_label }}</p>
                @if($order->payment->bank_name)
                    <p class="small mb-1"><strong>Bank / Pengirim:</strong> {{ $order->payment->bank_name }} - {{ $order->payment->account_name }}</p>
                @endif
                @if($order->payment->proof_url)
                    <a href="{{ $order->payment->proof_url }}" target="_blank" class="d-block my-2">
                        <img src="{{ $order->payment->proof_url }}" alt="Bukti" class="img-fluid rounded-3 border">
                    </a>
                @endif

                @if($order->payment->status === 'menunggu_verifikasi')
                    <div class="d-grid gap-2 mt-3">
                        <form action="{{ route('admin.payments.verify', $order->payment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-lpd-secondary btn-sm w-100">
                                <i class="bi bi-check-circle me-1"></i> Verifikasi Pembayaran
                            </button>
                        </form>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                            <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                        </button>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@if($order->payment)
<div class="modal fade" id="rejectPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.payments.reject', $order->payment) }}" method="POST" class="modal-content rounded-4 border-0">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-semibold">Alasan Penolakan</label>
                <textarea name="rejection_reason" rows="3" class="form-control" required placeholder="Misal: Nominal transfer tidak sesuai atau foto buram"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
