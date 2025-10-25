@extends('mobile.layouts.app')

@section('title', 'დროის არჩევა')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-clock"></i> აირჩიეთ დრო
                </h4>
                <p class="mb-0 mt-2 text-white-50">
                    {{ $restaurant->name }} - {{ $date }}
                </p>
            </div>
            <div class="card-body p-4">
                @if($slots->count() > 0)
                    <form method="POST" action="{{ route('mobile.booking.details', $restaurant->slug) }}">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="mb-4">
                            <label class="form-label">თავისუფალი დროები</label>
                            <div class="row g-2">
                                @foreach($slots as $slot)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="time" 
                                           id="time-{{ $loop->index }}" 
                                           value="{{ $slot }}" 
                                           required>
                                    <label class="btn btn-outline-primary w-100" for="time-{{ $loop->index }}">
                                        <i class="bi bi-clock"></i> {{ $slot }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('time')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-right"></i> გაგრძელება
                            </button>
                            <a href="{{ route('mobile.booking.select-date', $restaurant->slug) }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> უკან
                            </a>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h5 class="mt-3">თავისუფალი დრო არ არის</h5>
                        <p class="text-muted">გთხოვთ აირჩიოთ სხვა თარიღი</p>
                        <a href="{{ route('mobile.booking.select-date', $restaurant->slug) }}" 
                           class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> თარიღის შეცვლა
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-check:checked + .btn-outline-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
</style>
@endpush
@endsection
