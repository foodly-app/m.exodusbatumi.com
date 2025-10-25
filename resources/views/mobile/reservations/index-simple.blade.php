@extends('mobile.layouts.app')

@section('title', 'რეზერვაციები - FOODLY')

@section('content')
<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-1">
            <i class="bi bi-calendar-check"></i> რეზერვაციები
        </h4>
        <p class="text-muted mb-0 small">ყველა ჯავშანი</p>
    </div>
    <button class="btn btn-primary">
        <i class="bi bi-funnel"></i>
    </button>
</div>

<!-- Filter Tabs -->
<div class="card mb-3">
    <div class="card-header p-0">
        <nav class="nav nav-tabs card-header-tabs">
            <a class="nav-link active" href="#all" data-bs-toggle="tab">ყველა</a>
            <a class="nav-link" href="#today" data-bs-toggle="tab">დღეს</a>
            <a class="nav-link" href="#upcoming" data-bs-toggle="tab">მომავალი</a>
            <a class="nav-link" href="#past" data-bs-toggle="tab">წარსული</a>
        </nav>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-3">
    <div class="col-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="mb-1 text-primary">12</h5>
                <small class="text-muted">სულ</small>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="mb-1 text-success">8</h5>
                <small class="text-muted">დადასტურებული</small>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="mb-1 text-warning">3</h5>
                <small class="text-muted">მოლოდინში</small>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="mb-1 text-danger">1</h5>
                <small class="text-muted">გაუქმებული</small>
            </div>
        </div>
    </div>
</div>

<!-- Reservations List -->
<div class="tab-content">
    <div class="tab-pane fade show active" id="all">
        <!-- Today's Reservations -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">დღეს - 26 ოქტომბერი</h6>
            </div>
            <div class="list-group list-group-flush">
                <!-- Reservation Item 1 -->
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">გიორგი გელაშვილი</h6>
                                <span class="badge bg-success">დადასტურებული</span>
                            </div>
                            <p class="mb-2 small text-muted">
                                <i class="bi bi-clock"></i> 19:30
                                <i class="bi bi-people ms-3"></i> 4 სტუმარი
                                <i class="bi bi-telephone ms-3"></i> 599123456
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-geo-alt"></i> მაგიდა #12
                                <span class="ms-3 text-primary">#RES-001</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-success me-2">
                            <i class="bi bi-check-circle"></i> დასრულება
                        </button>
                        <button class="btn btn-sm btn-outline-warning me-2">
                            <i class="bi bi-credit-card"></i> ანგარიშსწორება
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-circle"></i> გაუქმება
                        </button>
                    </div>
                </div>

                <!-- Reservation Item 2 -->
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">ნინო ხახუტაშვილი</h6>
                                <span class="badge bg-warning">მოლოდინში</span>
                            </div>
                            <p class="mb-2 small text-muted">
                                <i class="bi bi-clock"></i> 20:00
                                <i class="bi bi-people ms-3"></i> 2 სტუმარი
                                <i class="bi bi-telephone ms-3"></i> 577654321
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-geo-alt"></i> მაგიდა #5
                                <span class="ms-3 text-primary">#RES-002</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-success me-2">
                            <i class="bi bi-check-circle"></i> დადასტურება
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-circle"></i> გაუქმება
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tomorrow's Reservations -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">ხვალ - 27 ოქტომბერი</h6>
            </div>
            <div class="list-group list-group-flush">
                <!-- Reservation Item 3 -->
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">მარიამ ჯაფარიძე</h6>
                                <span class="badge bg-success">დადასტურებული</span>
                            </div>
                            <p class="mb-2 small text-muted">
                                <i class="bi bi-clock"></i> 18:00
                                <i class="bi bi-people ms-3"></i> 6 სტუმარი
                                <i class="bi bi-telephone ms-3"></i> 555987654
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-geo-alt"></i> მაგიდა #8
                                <span class="ms-3 text-primary">#RES-003</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Empty State (Hidden by default) -->
<div class="text-center py-5 text-muted" style="display: none;">
    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
    <p class="mt-3 mb-3">რეზერვაციები არ იქმნება</p>
    <button class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> ახალი ჯავშანი
    </button>
</div>
@endsection