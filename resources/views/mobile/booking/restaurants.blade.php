@extends('mobile.layouts.app')

@section('title', 'რესტორნების არჩევა')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-shop"></i> აირჩიეთ რესტორანი</h4>
            </div>
            <div class="card-body">
                @if($restaurants->count() > 0)
                    <div class="row">
                        @foreach($restaurants as $restaurant)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: all 0.3s ease;">
                                @if($restaurant->image)
                                <img src="{{ $restaurant->image }}" class="card-img-top" alt="{{ $restaurant->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="bi bi-building text-white" style="font-size: 4rem;"></i>
                                </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                                    @if($restaurant->description)
                                    <p class="card-text text-muted small">{{ Str::limit($restaurant->description, 100) }}</p>
                                    @endif
                                    @if($restaurant->address)
                                    <p class="mb-1">
                                        <i class="bi bi-geo-alt text-primary"></i>
                                        <small>{{ $restaurant->address }}</small>
                                    </p>
                                    @endif
                                    @if($restaurant->phone)
                                    <p class="mb-3">
                                        <i class="bi bi-telephone text-primary"></i>
                                        <small>{{ $restaurant->phone }}</small>
                                    </p>
                                    @endif
                                    <a href="{{ route('mobile.booking.select-date', $restaurant->slug) }}" 
                                       class="btn btn-primary w-100">
                                        <i class="bi bi-calendar-plus"></i> ჯავშანი
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-shop display-1"></i>
                        <p class="mt-3">რესტორნები არ მოიძებნა</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
@endsection
