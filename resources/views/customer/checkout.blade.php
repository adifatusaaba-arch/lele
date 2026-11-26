@extends('layouts.app')

@section('title', 'Checkout - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-lpd-primary mb-4"><i class="bi bi-bag-check me-2"></i>Checkout Pesanan</h2>

    <form action="{{ route('customer.checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Alamat Pengiriman --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt text-lpd-secondary me-2"></i>1. Alamat Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($addresses->isNotEmpty())
                            <div class="row g-3 mb-3">
                                @foreach($addresses as $address)
                                    <div class="col-md-6">
                                        <label class="border rounded-3 p-3 d-block h-100" style="cursor: pointer;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_id" value="{{ $address->id }}" {{ $address->is_default || $loop->first ? 'checked' : '' }} onchange="toggleNewAddress(false)">
                                                <span class="fw-bold">{{ $address->label }}</span>
                                                @if($address->is_default)<span class="badge bg-lpd-secondary ms-1">Utama</span>@endif
                                            </div>
                                            <div class="small mt-2">
                                                <div class="fw-semibold">{{ $address->recipient_name }} ({{ $address->phone }})</div>
                                                <div class="text-muted">{{ $address->formatted_address }}</div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="address_id" value="" id="useNewAddress" onchange="toggleNewAddress(true)">
                                <label class="form-check-label fw-semibold text-lpd-primary" for="useNewAddress">
                                    + Gunakan Alamat Baru
                                </label>
                            </div>
                        @endif

                        <div id="newAddressFields" class="{{ $addresses->isNotEmpty() ? 'd-none' : '' }} row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Penerima</label>
                                <input type="text" name="recipient_name" class="form-control" value="{{ old('recipient_name', auth()->user()->name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nomor HP / WhatsApp</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Provinsi</label>
                                <input type="text" name="province" class="form-control" value="{{ old('province') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kota / Kabupaten</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kecamatan</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">Alamat Lengkap</label>
                                <input type="text" name="full_address" class="form-control" value="{{ old('full_address') }}" placeholder="Jalan, nomor rumah, RT/RW">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Metode Pengiriman --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-truck text-lpd-secondary me-2"></i>2. Metode Pengiriman</h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-2">
                        @foreach($shippingMethods as $key => $method)
                            <label class="border rounded-3 p-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                                <div class="form-check mb-0">
                                    <input class="form-check-input shipping-radio" type="radio" name="shipping_method" value="{{ $key }}" data-cost="{{ $method['cost'] }}" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="fw-semibold">{{ $method['label'] }}</span>
                                    <small class="d-block text-muted">Estimasi: {{ $method['estimate'] }}</small>
                                </div>
                                <span class="fw-bold text-lpd-primary">
                                    {{ $method['cost'] > 0 ? 'Rp ' . number_format($method['cost'], 0, ',', '.') : 'Gratis' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-credit-card text-lpd-secondary me-2"></i>3. Metode Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-2">
                            @foreach($paymentMethods as $key => $label)
                                <div class="col-md-6">
                                    <label class="border rounded-3 p-3 d-block" style="cursor: pointer;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                                            <span class="fw-semibold">{{ $label }}</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label class="form-label small fw-semibold">Catatan Pesanan (Opsional)</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Misal: minta dibersihkan, kirim jam 8 pagi...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 85px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-lpd-primary mb-3">Ringkasan Pesanan</h5>
                        <div class="d-flex flex-column gap-2 mb-3 pb-3 border-bottom">
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between small">
                                    <span>{{ $item->product->name }} ({{ $item->quantity }} {{ $item->product->unit }})</span>
                                    <span class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-semibold">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-semibold" id="shippingCostText">Rp 15.000</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total Bayar</span>
                            <span class="fw-bold fs-4 text-lpd-primary" id="grandTotalText">
                                Rp {{ number_format($cart->subtotal + ($shippingMethods['kurir_toko']['cost'] ?? 0), 0, ',', '.') }}
                            </span>
                        </div>
                        <button type="submit" class="btn btn-lpd-secondary btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>Buat Pesanan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleNewAddress(show) {
    const box = document.getElementById('newAddressFields');
    if (box) {
        box.classList.toggle('d-none', !show);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const subtotal = {{ (float) $cart->subtotal }};
    const radios = document.querySelectorAll('.shipping-radio');
    const shippingText = document.getElementById('shippingCostText');
    const totalText = document.getElementById('grandTotalText');

    function updateSummary() {
        const checked = document.querySelector('.shipping-radio:checked');
        const cost = checked ? parseFloat(checked.getAttribute('data-cost')) : 0;
        shippingText.textContent = cost > 0 ? 'Rp ' + cost.toLocaleString('id-ID') : 'Gratis';
        totalText.textContent = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
    }

    radios.forEach(r => r.addEventListener('change', updateSummary));
    updateSummary();
});
</script>
@endpush
