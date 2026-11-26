@extends('layouts.app')

@section('title', 'Masuk - LELE Pak Dedi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="bg-lpd-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-water fs-3"></i>
                        </span>
                        <h4 class="fw-bold text-lpd-primary mb-1">Masuk ke Akun Anda</h4>
                        <p class="text-muted small mb-0">Belanja ikan lele segar langsung dari kolam Pak Dedi</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lpd-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="fw-semibold text-lpd-secondary text-decoration-none">Daftar Gratis</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
