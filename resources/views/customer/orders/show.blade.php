@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number . ' - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <a href="{{ route('customer.orders.index') }}" class="text-decoration-none small text-lpd-primary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pesanan
            </a>
            <h3 class="fw-bold text-lpd-primary mb-0 mt-1">Pesanan {{ $order->order_number }}</h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-lpd-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Invoice
            </a>
            @if($order->status === 'dikirim')
                <form action="{{ route('customer.orders.confirm', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-lpd-secondary btn-sm">
                        <i class="bi bi-check2-circle me-1"></i> Pesanan Diterima
                    </button>
                </form>
            @endif
            @if(in_array($order->status, ['pending', 'menunggu_pembayaran']))
                <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Batalkan</button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Items --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Daftar Produk</h6>
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
                                    <td>
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        @if($item->fish_size)<small class="text-muted">Ukuran: {{ $item->fish_size }}</small>@endif
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }} / {{ $item->unit }}</td>
                                    <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end">Subtotal Produk</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">Ongkos Kirim</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Pembayaran</td>
                                <td class="text-end fw-bold fs-5 text-lpd-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Info Pengiriman --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold text-lpd-primary mb-3"><i class="bi bi-truck me-2"></i>Informasi Pengiriman</h6>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <div class="text-muted">Penerima</div>
                            <div class="fw-semibold">{{ $order->shipping_recipient }} ({{ $order->shipping_phone }})</div>
                            <div>{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Metode &amp; Status Pengiriman</div>
                            <div class="fw-semibold">{{ $order->shipping?->method_label }}</div>
                            <div>Status: <span class="badge bg-info text-dark">{{ $order->shipping?->status_label }}</span></div>
                            @if($order->shipping?->tracking_number)
                                <div>No. Resi: <span class="fw-bold">{{ $order->shipping->tracking_number }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pembayaran & Upload Bukti --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-lpd-primary mb-3"><i class="bi bi-credit-card me-2"></i>Pembayaran</h6>
                    <div class="small mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Metode:</span>
                            <span class="fw-semibold">{{ $order->payment?->method_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-secondary">{{ $order->payment?->status_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tagihan:</span>
                            <span class="fw-bold text-lpd-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->payment?->rejection_reason)
                        <div class="alert alert-danger small py-2">
                            <strong>Alasan Penolakan:</strong> {{ $order->payment->rejection_reason }}
                        </div>
                    @endif

                    @if($order->payment?->payment_method !== 'cod' && in_array($order->status, ['pending', 'menunggu_pembayaran', 'menunggu_verifikasi']))
                        <div class="bg-light rounded-3 p-3 small mb-3">
                            <div class="fw-bold mb-1">Rekening Tujuan Transfer:</div>
                            @foreach($bankAccounts as $bank => $info)
                                <div><strong>{{ $bank }}:</strong> {{ $info }}</div>
                            @endforeach
                        </div>

                        <form action="{{ route('customer.orders.payment', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Bank Pengirim</label>
                                <input type="text" name="bank_name" class="form-control form-control-sm" value="{{ old('bank_name', $order->payment?->bank_name) }}" placeholder="BCA / BRI / Mandiri / Dana" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Atas Nama Pengirim</label>
                                <input type="text" name="account_name" class="form-control form-control-sm" value="{{ old('account_name', auth()->user()->name) }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">No. Rekening (Opsional)</label>
                                <input type="text" name="account_number" class="form-control form-control-sm" value="{{ old('account_number') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Upload Bukti Transfer (JPG/PNG, maks 2MB)</label>
                                <input type="file" name="proof_image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-lpd-secondary w-100 btn-sm">
                                <i class="bi bi-upload me-1"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @endif

                    @if($order->payment?->proof_url)
                        <div class="mt-3 pt-3 border-top">
                            <div class="small fw-semibold mb-2">Bukti Transfer Terunggah:</div>
                            <a href="{{ $order->payment->proof_url }}" target="_blank">
                                <img src="{{ $order->payment->proof_url }}" alt="Bukti Transfer" class="img-fluid rounded-3 border">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
