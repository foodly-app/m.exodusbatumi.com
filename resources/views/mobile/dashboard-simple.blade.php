@extends('mobile.layouts.app')

@section('title', 'მთავარი - FOODLY')

@section('content')
<!-- Welcome Card -->
<div class="card mb-3">
    <div class="card-body">
        <h4 class="mb-2">
            <i class="bi bi-emoji-smile"></i> გამარჯობა, {{ session('partner_user.name', 'მენეჯერი') }}!
        </h4>
        <p class="text-muted mb-0 small">კეთილი იყოს თქვენი დაბრუნება FOODLY-ში</p>
        @if(session('success'))
            <div class="alert alert-success mt-2 mb-0">{{ session('success') }}</div>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-3">
    <div class="col-6">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                <h3 class="fw-bold mt-2 mb-0">0</h3>
                <p class="mb-0 small">მომავალი</p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-check-fill" style="font-size: 2rem;"></i>
                <h3 class="fw-bold mt-2 mb-0">0</h3>
                <p class="mb-0 small">სულ</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-lightning-charge"></i> სწრაფი მოქმედებები
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6">
                <a href="#" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-calendar-plus d-block mb-2" style="font-size: 1.5rem;"></i>
                    ახალი ჯავშანი
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('mobile.reservations.index') }}" class="btn btn-outline-success w-100 py-3">
                    <i class="bi bi-list-check d-block mb-2" style="font-size: 1.5rem;"></i>
                    რეზერვაციები
                </a>
            </div>
            <div class="col-6">
                <a href="#" class="btn btn-outline-info w-100 py-3">
                    <i class="bi bi-graph-up d-block mb-2" style="font-size: 1.5rem;"></i>
                    სტატისტიკა
                </a>
            </div>
            <div class="col-6">
                <a href="#" class="btn btn-outline-warning w-100 py-3">
                    <i class="bi bi-gear d-block mb-2" style="font-size: 1.5rem;"></i>
                    პარამეტრები
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Status -->
{{-- <div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-info-circle"></i> სისტემის ინფორმაცია
        </h5>
    </div>
    <div class="card-body">
        <p class="mb-2"><strong>მომხმარებელი:</strong> {{ session('partner_user.email', 'N/A') }}</p>
        <p class="mb-2"><strong>სტატუსი:</strong> <span class="badge bg-success">აქტიური</span></p>
        @if(isset($error))
            <div class="alert alert-warning mt-3 mb-0">
                <i class="bi bi-exclamation-triangle"></i> {{ $error }}
            </div>
        @endif
    </div>
</div> --}}
@endsection