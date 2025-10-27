@extends('mobile.layouts.app')

@section('title', 'რეზერვაციების სია')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-calendar-check"></i> ჩემი რეზერვაციები</h4>
                {{-- <a href="{{ route('mobile.booking.restaurants') }}" class="btn btn-primary"> --}}
                    <i class="bi bi-plus-circle"></i> ახალი ჯავშანი
                </a>
            </div>
            <div class="card-body">
                <!-- Filter Tabs -->
                <div class="d-flex gap-2 mb-4" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <a class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline-primary' }} flex-shrink-0" 
                       href="{{ route('mobile.reservations.index') }}"
                       style="border-radius: 20px; padding: 0.5rem 1.25rem; font-weight: 600;">
                        <i class="bi bi-list-ul"></i> ყველა
                    </a>
                    <a class="btn {{ request('filter') == 'active' ? 'btn-primary' : 'btn-outline-primary' }} flex-shrink-0" 
                       href="{{ route('mobile.reservations.index', ['filter' => 'active']) }}"
                       style="border-radius: 20px; padding: 0.5rem 1.25rem; font-weight: 600;">
                        <i class="bi bi-play-circle"></i> მიმდინარე
                    </a>
                    <a class="btn {{ request('filter') == 'completed' ? 'btn-primary' : 'btn-outline-primary' }} flex-shrink-0" 
                       href="{{ route('mobile.reservations.index', ['filter' => 'completed']) }}"
                       style="border-radius: 20px; padding: 0.5rem 1.25rem; font-weight: 600;">
                        <i class="bi bi-check-circle"></i> დასრულებული
                    </a>
                </div>

                @if(is_array($reservations) && count($reservations) > 0)
                    <div class="row">
                        @foreach($reservations as $reservation)
                        <div class="col-md-6 mb-3">
                            <div class="card reservation-card h-100">
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
                                        <strong>დრო:</strong> {{ substr($reservation->time_from, 0, 5) }} - {{ substr($reservation->time_to, 0, 5) }}
                                    </div>
                                    
                                    <div class="mb-3">
                                        <i class="bi bi-people text-primary"></i>
                                        <strong>სტუმრები:</strong> {{ $reservation->guests_count }}
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('mobile.reservations.show', $reservation->id) }}" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="bi bi-eye"></i> დეტალები
                                        </a>
                                        @if($reservation->status->value === 'confirmed')
                                        <a href="{{ route('mobile.payments.initiate', $reservation->id) }}" 
                                           class="btn btn-success btn-sm">
                                            <i class="bi bi-credit-card"></i> გადახდა
                                        </a>
                                        @endif
                                        @if(!in_array($reservation->status->value, ['cancelled', 'completed']))
                                            @if($reservation->status->value === 'paid')
                                            <form action="{{ route('mobile.reservations.cancel', $reservation->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('დარწმუნებული ხართ რომ გსურთ გაუქმება?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm opacity-50" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                                    <i class="bi bi-x-circle" style="font-size: 0.7rem;"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('mobile.reservations.cancel', $reservation->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('დარწმუნებული ხართ რომ გსურთ გაუქმება?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> გაუქმება
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reservations->links() }}
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-1"></i>
                        <p class="mt-3">რეზერვაციები არ მოიძებნა</p>
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
