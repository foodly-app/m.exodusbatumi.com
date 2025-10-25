@extends('mobile.layouts.app')

@section('title', 'თარიღის არჩევა')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-calendar"></i> აირჩიეთ თარიღი
                </h4>
                <p class="mb-0 mt-2 text-white-50">{{ $restaurant->name }}</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('mobile.booking.select-time', $restaurant->slug) }}">
                    @csrf

                    <div class="mb-4">
                        <label for="date" class="form-label">რეზერვაციის თარიღი</label>
                        <input type="date" 
                               class="form-control form-control-lg @error('date') is-invalid @enderror" 
                               id="date" 
                               name="date" 
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('date', date('Y-m-d')) }}"
                               required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle"></i> აირჩიეთ თარიღი დღევანდან მოყოლებული
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right"></i> გაგრძელება
                        </button>
                        <a href="{{ route('mobile.booking.restaurants') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> უკან
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
