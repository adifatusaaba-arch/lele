@extends('layouts.app')

@section('title', 'Alamat Pengiriman - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-lpd-primary mb-0">Alamat Pengiriman</h3>
            <p class="text-muted small mb-0">Kelola alamat pengiriman ikan lele Anda</p>
        </div>
        <button class="btn btn-lpd-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Alamat
        </button>
    </div>

    <div class="row g-3">
        @forelse($addresses as $address)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 {{ $address->is_default ? 'border border-2 border-success' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-lpd-primary">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="badge bg-lpd-secondary">Alamat Utama</span>
                                @endif
                            </div>
                            <div class="d-flex gap-1">
                                @if(! $address->is_default)
                                    <form action="{{ route('customer.addresses.default', $address) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success">Jadikan Utama</button>
                                    </form>
                                @endif
                                <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $address->recipient_name }}</h6>
                        <p class="small text-muted mb-1"><i class="bi bi-telephone me-1"></i>{{ $address->phone }}</p>
                        <p class="small mb-1">{{ $address->formatted_address }}</p>
                        @if($address->notes)
                            <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Catatan: {{ $address->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-geo-alt fs-1 text-muted mb-2"></i>
                    <h6 class="fw-bold">Belum ada alamat pengiriman</h6>
                    <p class="text-muted small mb-3">Tambahkan alamat Anda untuk mempermudah proses checkout.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Alamat --}}
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('customer.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Alamat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Label Alamat</label>
                        <input type="text" name="label" class="form-control" placeholder="Rumah / Warung" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nama Penerima</label>
                        <input type="text" name="recipient_name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Provinsi</label>
                        <input type="text" name="province" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kota / Kabupaten</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <input type="text" name="district" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode Pos</label>
                        <input type="text" name="postal_code" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Catatan / Patokan (Opsional)</label>
                        <input type="text" name="notes" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="full_address" rows="2" class="form-control" placeholder="Nama jalan, nomor rumah, RT/RW" required></textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultAddr" checked>
                            <label class="form-check-label" for="isDefaultAddr">Jadikan alamat utama</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-lpd-primary">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
