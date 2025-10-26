@extends('mobile.layouts.app')

@section('title', 'რეზერვაციები - FOODLY')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-calendar-check text-primary"></i> რეზერვაციები
            </h4>
            <p class="text-muted mb-0 small">ჯავშნების მართვა</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel"></i> ფილტრი
        </button>
    </div>

    <!-- Modern Filter Tabs -->
    <div class="mb-3">
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="filter" id="filter_all" autocomplete="off" checked>
            <label class="btn btn-outline-primary" for="filter_all" onclick="filterReservations('all')">
                <i class="bi bi-list-ul"></i>
                <div class="small">ყველა</div>
            </label>

            <input type="radio" class="btn-check" name="filter" id="filter_today" autocomplete="off">
            <label class="btn btn-outline-primary" for="filter_today" onclick="filterReservations('today')">
                <i class="bi bi-calendar-day"></i>
                <div class="small">დღეს</div>
            </label>

            <input type="radio" class="btn-check" name="filter" id="filter_upcoming" autocomplete="off">
            <label class="btn btn-outline-primary" for="filter_upcoming" onclick="filterReservations('upcoming')">
                <i class="bi bi-calendar-plus"></i>
                <div class="small">მომავალი</div>
            </label>

            <input type="radio" class="btn-check" name="filter" id="filter_past" autocomplete="off">
            <label class="btn btn-outline-primary" for="filter_past" onclick="filterReservations('past')">
                <i class="bi bi-calendar-minus"></i>
                <div class="small">წარსული</div>
            </label>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-3">
        <div class="col-3">
            <div class="card text-center border-primary">
                <div class="card-body p-2">
                    <h4 class="mb-0 text-primary">12</h4>
                    <small class="text-muted d-block" style="font-size: 0.7rem;">სულ</small>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center border-success">
                <div class="card-body p-2">
                    <h4 class="mb-0 text-success">8</h4>
                    <small class="text-muted d-block" style="font-size: 0.7rem;">დადასტ.</small>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center border-warning">
                <div class="card-body p-2">
                    <h4 class="mb-0 text-warning">3</h4>
                    <small class="text-muted d-block" style="font-size: 0.7rem;">მოლოდინი</small>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center border-danger">
                <div class="card-body p-2">
                    <h4 class="mb-0 text-danger">1</h4>
                    <small class="text-muted d-block" style="font-size: 0.7rem;">გაუქმებ.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations List -->
    <div id="reservations-container">
        <!-- Today's Reservations -->
        <div class="reservation-group" data-filter="all today">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-calendar-day"></i> დღეს - 26 ოქტომბერი
                </h6>
                <span class="badge bg-primary">3 ჯავშანი</span>
            </div>
            
            <!-- Reservation Card 1 -->
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px; font-size: 18px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">გიორგი გელაშვილი</h6>
                                <small class="text-muted">#RES-001</small>
                            </div>
                        </div>
                        <span class="badge bg-success">დადასტურებული</span>
                    </div>
                    
                    <div class="row g-2 mb-2 small">
                        <div class="col-6">
                            <i class="bi bi-clock text-primary"></i> 19:30
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-primary"></i> 4 სტუმარი
                        </div>
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-primary"></i> მაგიდა #12
                        </div>
                        <div class="col-6">
                            <i class="bi bi-telephone text-primary"></i> 599123456
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-success flex-fill">
                            <i class="bi bi-check-circle"></i> დასრულება
                        </button>
                        {{-- <button class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="bi bi-credit-card"></i> გადახდა
                        </button> --}}
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reservation Card 2 -->
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px; font-size: 18px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">ნინო ხახუტაშვილი</h6>
                                <small class="text-muted">#RES-002</small>
                            </div>
                        </div>
                        <span class="badge bg-warning">მოლოდინში</span>
                    </div>
                    
                    <div class="row g-2 mb-2 small">
                        <div class="col-6">
                            <i class="bi bi-clock text-primary"></i> 20:00
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-primary"></i> 2 სტუმარი
                        </div>
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-primary"></i> მაგიდა #5
                        </div>
                        <div class="col-6">
                            <i class="bi bi-telephone text-primary"></i> 577654321
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary flex-fill">
                            <i class="bi bi-check-circle"></i> დადასტურება
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-fill">
                            <i class="bi bi-x-circle"></i> გაუქმება
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reservation Card 3 -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px; font-size: 18px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">დავით მესხი</h6>
                                <small class="text-muted">#RES-004</small>
                            </div>
                        </div>
                        <span class="badge bg-success">დადასტურებული</span>
                    </div>
                    
                    <div class="row g-2 mb-2 small">
                        <div class="col-6">
                            <i class="bi bi-clock text-primary"></i> 21:00
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-primary"></i> 3 სტუმარი
                        </div>
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-primary"></i> მაგიდა #7
                        </div>
                        <div class="col-6">
                            <i class="bi bi-telephone text-primary"></i> 555111222
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-success flex-fill">
                            <i class="bi bi-check-circle"></i> დასრულება
                        </button>
                        {{-- <button class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="bi bi-credit-card"></i> გადახდა
                        </button> --}}
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Reservations -->
        <div class="reservation-group" data-filter="all upcoming">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0 text-success">
                    <i class="bi bi-calendar-plus"></i> ხვალ - 27 ოქტომბერი
                </h6>
                <span class="badge bg-success">2 ჯავშანი</span>
            </div>
            
            <!-- Reservation Card 4 -->
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px; font-size: 18px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">მარიამ ჯაფარიძე</h6>
                                <small class="text-muted">#RES-003</small>
                            </div>
                        </div>
                        <span class="badge bg-success">დადასტურებული</span>
                    </div>
                    
                    <div class="row g-2 mb-2 small">
                        <div class="col-6">
                            <i class="bi bi-clock text-primary"></i> 18:00
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-primary"></i> 6 სტუმარი
                        </div>
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-primary"></i> მაგიდა #8
                        </div>
                        <div class="col-6">
                            <i class="bi bi-telephone text-primary"></i> 555987654
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info flex-fill">
                            <i class="bi bi-eye"></i> დეტალები
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-fill">
                            <i class="bi bi-x-circle"></i> გაუქმება
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reservation Card 5 -->
            <!-- Reservation Card 5 -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px; font-size: 18px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">ლევან თედიაშვილი</h6>
                                <small class="text-muted">#RES-005</small>
                            </div>
                        </div>
                        <span class="badge bg-success">დადასტურებული</span>
                    </div>
                    
                    <div class="row g-2 mb-2 small">
                        <div class="col-6">
                            <i class="bi bi-clock text-primary"></i> 20:30
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-primary"></i> 4 სტუმარი
                        </div>
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-primary"></i> მაგიდა #15
                        </div>
                        <div class="col-6">
                            <i class="bi bi-telephone text-primary"></i> 599456789
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info flex-fill">
                            <i class="bi bi-eye"></i> დეტალები
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-fill">
                            <i class="bi bi-x-circle"></i> გაუქმება
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Past Reservations -->
        <div class="reservation-group" data-filter="all past">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-calendar-minus"></i> გუშინ - 25 ოქტომბერი
                </h6>
                <span class="badge bg-secondary">1 ჯავშანი</span>
            </div>
            
            <!-- Past Reservation Card -->
            <div class="card mb-3 shadow-sm border-secondary">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px; font-size: 18px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">ანა კვარაცხელია</h6>
                                <small class="text-muted">#RES-000</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary">დასრულებული</span>
                    </div>
                    
                    <div class="row g-2 mb-2 small">
                        <div class="col-6">
                            <i class="bi bi-clock text-muted"></i> 19:00
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-muted"></i> 2 სტუმარი
                        </div>
                        <div class="col-6">
                            <i class="bi bi-geo-alt text-muted"></i> მაგიდა #3
                        </div>
                        <div class="col-6">
                            <i class="bi bi-check-circle text-success"></i> გადახდილი
                        </div>
                    </div>
                    
                    <button class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-eye"></i> დეტალების ნახვა
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="text-center py-5 text-muted" style="display: none;">
        <i class="bi bi-calendar-x" style="font-size: 4rem; opacity: 0.3;"></i>
        <h5 class="mt-3 mb-2">რეზერვაციები არ მოიძებნა</h5>
        <p class="mb-3 small">არჩეული ფილტრით რეზერვაციები არ არის</p>
        <button class="btn btn-primary" onclick="filterReservations('all')">
            <i class="bi bi-arrow-clockwise"></i> ყველას ნახვა
        </button>
    </div>
</div>

@push('scripts')
<script>
    // Filter reservations
    function filterReservations(filter) {
        const groups = document.querySelectorAll('.reservation-group');
        const emptyState = document.getElementById('empty-state');
        let visibleCount = 0;
        
        groups.forEach(group => {
            const filters = group.getAttribute('data-filter').split(' ');
            
            if (filter === 'all' || filters.includes(filter)) {
                group.style.display = 'block';
                visibleCount++;
            } else {
                group.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
        
        // Update active button
        document.querySelectorAll('[name="filter"]').forEach(btn => {
            btn.checked = false;
        });
        document.getElementById('filter_' + filter).checked = true;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        filterReservations('all');
    });
</script>
@endpush
@endsection