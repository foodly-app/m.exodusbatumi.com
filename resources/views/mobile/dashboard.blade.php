@extends('mobile.layouts.app')

@section('title', 'მთავარი - FOODLY')

@section('content')
<!-- Welcome Card -->
<div class="card mb-3">
    <div class="card-body">
        <h4 class="mb-2">
            <i class="bi bi-emoji-smile"></i> გამარჯობა, {{ Auth::user()->name }}!
        </h4>
        <p class="text-muted mb-0 small">კეთილი იყოს თქვენი დაბრუნება FOODLY-ში</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-3">
    <div class="col-6">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                <h3 class="fw-bold mt-2 mb-0">{{ $upcomingCount }}</h3>
                <p class="mb-0 small">მომავალი</p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-check-fill" style="font-size: 2rem;"></i>
                <h3 class="fw-bold mt-2 mb-0">{{ Auth::user()->reservations()->count() }}</h3>
                <p class="mb-0 small">სულ</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-lightning-charge"></i> სწრაფი მოქმედებები</h6>
    </div>
    <div class="card-body p-2">
        <div class="row g-2">
            <div class="col-4">
                <a href="{{ route('mobile.booking.restaurants') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                    <i class="bi bi-plus-circle fs-4"></i>
                    <small class="mt-1">ახალი</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.reservations.upcoming') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                    <i class="bi bi-calendar-event fs-4"></i>
                    <small class="mt-1">მომავალი</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.payments.history') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                    <i class="bi bi-receipt fs-4"></i>
                    <small class="mt-1">გადახდები</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Reservations -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history"></i> ბოლო რეზერვაციები</h6>
        <a href="{{ route('mobile.reservations.index') }}" class="btn btn-sm btn-outline-primary">
            ყველა <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body p-0">
        @if($recentReservations->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($recentReservations as $reservation)
                <a href="{{ route('mobile.reservations.show', $reservation->id) }}" 
                   class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-1 fw-bold">{{ $reservation->reservable->name ?? 'N/A' }}</h6>
                        <span class="badge bg-{{ $reservation->status->value }} ms-2">{{ ucfirst($reservation->status->value) }}</span>
                    </div>
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-calendar"></i> {{ $reservation->reservation_date }}
                        <i class="bi bi-clock ms-2"></i> {{ substr($reservation->time_from, 0, 5) }}
                        <i class="bi bi-people ms-2"></i> {{ $reservation->guests_count }} სტუმარი
                    </p>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3 mb-3">რეზერვაციები არ გაქვთ</p>
                <a href="{{ route('mobile.booking.restaurants') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> შექმენით პირველი ჯავშანი
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
