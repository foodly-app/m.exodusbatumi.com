@extends('mobile.layouts.app')

@section('title', 'რეზერვაციის დეტალები')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-file-text"></i> რეზერვაციის დეტალები</h4>
                <span class="badge bg-{{ $reservation->status->value }} fs-6">{{ ucfirst($reservation->status->value) }}</span>
            </div>
            <div class="card-body">
                <!-- Restaurant Info -->
                <div class="mb-4">
                    <h5 class="text-primary"><i class="bi bi-building"></i> რესტორანი</h5>
                    <h3>{{ $reservation->reservable->name ?? 'N/A' }}</h3>
                    @if($reservation->display_id)
                    <p class="text-muted mb-0">შეკვეთის ID: <strong>{{ $reservation->display_id }}</strong></p>
                    @endif
                </div>

                <hr>

                <!-- Reservation Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="bi bi-calendar"></i> თარიღი</h6>
                        <p class="h5">{{ $reservation->reservation_date }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="bi bi-clock"></i> დრო</h6>
                        <p class="h5">{{ substr($reservation->time_from, 0, 5) }} - {{ substr($reservation->time_to, 0, 5) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="bi bi-people"></i> სტუმრების რაოდენობა</h6>
                        <p class="h5">{{ $reservation->guests_count }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="bi bi-currency-exchange"></i> ღირებულება</h6>
                        <p class="h5">{{ $reservation->total_price }} GEL</p>
                    </div>
                </div>

                <hr>

                <!-- Customer Info -->
                <div class="mb-4">
                    <h6 class="text-primary"><i class="bi bi-person-badge"></i> კონტაქტური ინფორმაცია</h6>
                    <p><strong>სახელი:</strong> {{ $reservation->name }}</p>
                    <p><strong>ტელეფონი:</strong> {{ $reservation->phone }}</p>
                    @if($reservation->email)
                    <p><strong>ელ. ფოსტა:</strong> {{ $reservation->email }}</p>
                    @endif
                </div>

                @if($reservation->notes)
                <hr>
                <div class="mb-4">
                    <h6 class="text-primary"><i class="bi bi-chat-left-text"></i> შენიშვნები</h6>
                    <p>{{ $reservation->notes }}</p>
                </div>
                @endif

                @if($reservation->payment)
                <hr>
                <div class="mb-4">
                    <h6 class="text-primary"><i class="bi bi-credit-card"></i> გადახდის ინფორმაცია</h6>
                    <p><strong>სტატუსი:</strong> <span class="badge bg-{{ $reservation->payment->status }}">{{ ucfirst($reservation->payment->status) }}</span></p>
                    <p><strong>თანხა:</strong> {{ $reservation->payment->amount }} {{ $reservation->payment->currency }}</p>
                    <p><strong>მეთოდი:</strong> {{ strtoupper($reservation->payment->payment_method) }}</p>
                </div>
                @endif

                <hr>

                <!-- Actions -->
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('mobile.reservations.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> უკან
                    </a>
                    
                    @if($reservation->status->value == 'confirmed' && !$reservation->payment_id)
                    <a href="{{ route('mobile.payments.initiate', $reservation->id) }}" class="btn btn-success">
                        <i class="bi bi-credit-card"></i> გადახდა
                    </a>
                    @endif

                    @if(!in_array($reservation->status->value, ['cancelled', 'completed']))
                        @if($reservation->status->value == 'paid')
                        <form action="{{ route('mobile.reservations.cancel', $reservation->id) }}" 
                              method="POST"
                              onsubmit="return confirm('დარწმუნებული ხართ რომ გსურთ რეზერვაციის გაუქმება?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary opacity-50" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                                <i class="bi bi-x-circle" style="font-size: 0.75rem;"></i>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('mobile.reservations.cancel', $reservation->id) }}" 
                              method="POST" 
                              class="flex-fill"
                              onsubmit="return confirm('დარწმუნებული ხართ რომ გსურთ რეზერვაციის გაუქმება?')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle"></i> გაუქმება
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
