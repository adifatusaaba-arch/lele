@extends('layouts.app')

@section('title', 'Daftar Pelanggan - LELE Pak Dedi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="bg-lpd-secondary text-white rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-person-plus fs-3"></i>
                        </span>
                        <h4 class="fw-bold text-lpd-primary mb-1">Buat Akun Baru</h4>
                        <p class="text-muted small mb-0">Daftar untuk mulai memesan lele segar & bibit unggul</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor WhatsApp / HP</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0812xxxxxxxx" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>

                        <button type="submit" class="btn btn-lpd-secondary w-100 py-2">
                            <i class="bi bi-check-circle me-1"></i> Daftar Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="fw-semibold text-lpd-primary text-decoration-none">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
