@extends('layouts.app')

@section('title', 'Profil Saya - LELE Pak Dedi')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold text-lpd-primary mb-0"><i class="bi bi-person-circle me-2"></i>Profil Pelanggan</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor WhatsApp / HP</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-muted mb-3">Ubah Password (Opsional)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengubah">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-lpd-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
