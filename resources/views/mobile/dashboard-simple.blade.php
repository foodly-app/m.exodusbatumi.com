@extends('mobile.layouts.app')

@section('title', 'მთავარი - FOODLY')

@section('content')
<div class="container-fluid px-0">
    @if(isset($error))
    <!-- Error Alert -->
    <div class="alert alert-danger mb-3">
        <i class="bi bi-exclamation-triangle"></i> {{ $error }}
    </div>
    @endif

    @if(session('success'))
    <!-- Success Alert -->
    {{-- <div class="alert alert-success mb-3">
        <i class="bi bi-check-circle"></i> გამარჯობა, {{ session('success') }} 
    </div> --}}
    @endif

    <!-- Welcome Card -->
    <div class="card mb-3 border-0 shadow-sm" style="background: linear-gradient(135deg, var(--foodly-primary) 0%, var(--foodly-dark) 100%);">
        <div class="card-body text-center text-white py-3">
            <h5 class="mb-2 fw-bold">
                FOODLY <br> მართე რესტორანი ციფრულად
            </h5>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap small">
                <span class="badge bg-white bg-opacity-25 px-2 py-1">
                    <i class="bi bi-calendar-event me-1"></i>{{ now()->translatedFormat('d F Y') }}
                </span>
                <span class="badge bg-white bg-opacity-25 px-2 py-1">
                    <i class="bi bi-clock me-1"></i><span id="current-time">{{ now()->format('H:i:s') }}</span>
                </span>
            </div>
        </div>
    </div>

    @if(isset($dashboard))
    <!-- Main Content -->
    <div id="dashboard-content">
        <!-- Stats Cards -->
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0">{{ $dashboard['today_stats']['total_reservations'] ?? 0 }}</h2>
                        <p class="mb-0 small">დღეს</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0">{{ $dashboard['upcoming_reservations'] ?? 0 }}</h2>
                        <p class="mb-0 small">მომავალი</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-grid-3x3" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0">{{ $dashboard['tables']['active'] ?? 0 }}/{{ $dashboard['tables']['total'] ?? 0 }}</h2>
                        <p class="mb-0 small">მაგიდები</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-map-pin" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0">{{ $dashboard['places']['active'] ?? 0 }}/{{ $dashboard['places']['total'] ?? 0 }}</h2>
                        <p class="mb-0 small">Places</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Summary -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="mb-3">
                    <i class="bi bi-pie-chart text-primary"></i> სტატუსის განაწილება (დღეს)
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-2 me-2">
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">დადასტურებული</small>
                                <strong>{{ $dashboard['today_stats']['confirmed'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-2">
                                <i class="bi bi-clock text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">მოლოდინში</small>
                                <strong>{{ $dashboard['today_stats']['pending'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle p-2 me-2">
                                <i class="bi bi-x-circle text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">გაუქმებული</small>
                                <strong>{{ $dashboard['today_stats']['cancelled'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle p-2 me-2">
                                <i class="bi bi-check-all text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">დასრულებული</small>
                                <strong>{{ $dashboard['today_stats']['completed'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center p-2">
                <h6 class="mb-0 small">
                    <i class="bi bi-calendar-day text-primary"></i> ბოლო რეზერვაციები
                </h6>
                <a href="{{ route('mobile.reservations.index') }}" class="btn btn-sm btn-outline-primary">
                    ყველა
                </a>
            </div>
            <div class="card-body p-0">
                @if(isset($dashboard['recent_reservations']) && count($dashboard['recent_reservations']) > 0)
                <div class="list-group list-group-flush">
                    @foreach($dashboard['recent_reservations'] as $reservation)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $reservation['name'] }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-phone"></i> {{ $reservation['phone'] }}
                                </small>
                                <div class="mt-1">
                                    <small>
                                        <i class="bi bi-calendar"></i> {{ $reservation['reservation_date'] }}
                                        <i class="bi bi-clock ms-2"></i> {{ $reservation['time_from'] }} - {{ $reservation['time_to'] }}
                                    </small>
                                </div>
                                <div class="mt-1">
                                    <small><i class="bi bi-people"></i> {{ $reservation['guests_count'] }} სტუმარი</small>
                                </div>
                            </div>
                            <div>
                                @if($reservation['status'] === 'confirmed')
                                <span class="badge bg-success">დადასტურებული</span>
                                @elseif($reservation['status'] === 'pending')
                                <span class="badge bg-warning">მოლოდინში</span>
                                @elseif($reservation['status'] === 'cancelled')
                                <span class="badge bg-danger">გაუქმებული</span>
                                @else
                                <span class="badge bg-secondary">დასრულებული</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">რეზერვაციები არ არის</p>
                </div>
                @endif
            </div>
        </div>
        
        @if(isset($restaurants) && count($restaurants) > 0)
        <!-- Restaurant Selector -->
        <div class="card mb-3">
            <div class="card-header p-2">
                <h6 class="mb-0 small">
                    <i class="bi bi-shop text-primary"></i> რესტორნები
                </h6>
            </div>
            <div class="card-body p-2">
                <select class="form-select" id="restaurant-selector">
                    @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant['id'] }}" 
                        {{ isset($selectedRestaurant) && $selectedRestaurant['id'] == $restaurant['id'] ? 'selected' : '' }}>
                        {{ $restaurant['name'] }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header p-2">
                <h6 class="mb-0 small">
                    <i class="bi bi-lightning-charge text-warning"></i> სწრაფი მოქმედებები
                </h6>
            </div>
            <div class="card-body p-2">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-primary w-100 py-2">
                            <i class="bi bi-calendar-plus d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>ახალი ჯავშანი</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('mobile.reservations.index') }}" class="btn btn-outline-success w-100 py-2">
                            <i class="bi bi-list-check d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>რეზერვაციები</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('mobile.restaurant.working-hours') }}" class="btn btn-outline-info w-100 py-2">
                            <i class="bi bi-clock-history d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>სამუშაო საათები</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('mobile.settings.index') }}" class="btn btn-outline-warning w-100 py-2">
                            <i class="bi bi-gear d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>პარამეტრები</small>
                        </a>
                    </div>
                </div>
            </div>

    @else
    <!-- No Dashboard Data -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> დაშბორდის მონაცემები არ არის ხელმისაწვდომი
    </div>
    @endif
</div>

@push('scripts')
<script>
// Real-time clock update
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const timeString = `${hours}:${minutes}:${seconds}`;
    
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

// Update time every second
setInterval(updateTime, 1000);

// Restaurant selector change handler
document.addEventListener('DOMContentLoaded', function() {
    // Initialize time
    updateTime();
    
    const selector = document.getElementById('restaurant-selector');
    if (selector) {
        selector.addEventListener('change', function() {
            const restaurantId = this.value;
            // Reload page or fetch new data for selected restaurant
            console.log('Selected restaurant:', restaurantId);
            // You can add AJAX call here to refresh dashboard data for selected restaurant
        });
    }
});
</script>
@endpush
@endsection