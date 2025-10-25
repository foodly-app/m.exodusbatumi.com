@extends('mobile.layouts.app')

@section('title', 'მომავალი რეზერვაციები')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-calendar-event"></i> მომავალი რეზერვაციები</h4>
            </div>
            <div class="card-body">
                @if($reservations->count() > 0)
                    <div class="row">
                        @foreach($reservations as $reservation)
                        <div class="col-md-6 mb-3">
                            <div class="card reservation-card h-100 border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0">{{ $reservation->reservable->name ?? 'N/A' }}</h5>
                                        <span class="badge bg-{{ $reservation->status->value }}">
                                            {{ ucfirst($reservation->status->value) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <i class="bi bi-calendar text-primary"></i>
                                        <strong>თარიღი:</strong> {{ $reservation->reservation_date }}
                                    </div>
                                    
                                    <div class="mb-2">
                                        <i class="bi bi-clock text-primary"></i>
                                        <strong>დრო:</strong> {{ substr($reservation->time_from, 0, 5) }}
                                    </div>
                                    
                                    <div class="mb-3">
                                        <i class="bi bi-people text-primary"></i>
                                        <strong>სტუმრები:</strong> {{ $reservation->guests_count }}
                                    </div>

                                    @if($reservation->total_price > 0)
                                    <div class="mb-3">
                                        <i class="bi bi-currency-exchange text-success"></i>
                                        <strong>ღირებულება:</strong> {{ $reservation->total_price }} GEL
                                    </div>
                                    @endif
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('mobile.reservations.show', $reservation->id) }}" 
                                           class="btn btn-primary btn-sm flex-fill">
                                            <i class="bi bi-eye"></i> დეტალები
                                        </a>
                                        
                                        @if($reservation->status->value === 'confirmed' && !$reservation->payment_id)
                                        <a href="{{ route('mobile.payments.initiate', $reservation->id) }}" 
                                           class="btn btn-success btn-sm flex-fill">
                                            <i class="bi bi-credit-card"></i> გადახდა
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x display-1"></i>
                        <p class="mt-3">მომავალი რეზერვაციები არ გაქვთ</p>
                        <a href="{{ route('mobile.booking.restaurants') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> შექმენით ახალი ჯავშანი
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
