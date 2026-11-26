@extends('layouts.admin')

@section('title', 'Pengaturan Toko - Admin LELE Pak Dedi')
@section('page_title', 'Pengaturan Toko')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    @foreach($settings as $setting)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ $setting->label ?? $setting->key }}</label>
                            @if($setting->type === 'textarea')
                                <textarea name="settings[{{ $setting->key }}]" rows="3" class="form-control">{{ $setting->value }}</textarea>
                            @else
                                <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}" name="settings[{{ $setting->key }}]" class="form-control" value="{{ $setting->value }}">
                            @endif
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-lpd-primary mt-2">
                        <i class="bi bi-save me-1"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
