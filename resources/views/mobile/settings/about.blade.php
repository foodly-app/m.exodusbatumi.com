@extends('mobile.layouts.app')

@section('title', 'აპლიკაციის შესახებ - FOODLY')

@section('content')
<div class="container-fluid px-0">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('mobile.settings.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> უკან
        </a>
    </div>

    <!-- App Logo -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo/RedDit.png') }}" alt="FOODLY" style="width: 100px; height: 100px;">
        <h4 class="mt-3 mb-0">FOODLY Partner Panel</h4>
        <p class="text-muted">Mobile Management System</p>
    </div>

    <!-- Version Info -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-info-circle"></i> ვერსიის ინფორმაცია
            </h6>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ვერსია</span>
                <strong>1.0.0</strong>
            </div>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Build</span>
                <strong>100</strong>
            </div>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">გამოშვების თარიღი</span>
                <strong>21 ოქტომბერი, 2025</strong>
            </div>
            
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Laravel Framework</span>
                <strong>{{ app()->version() }}</strong>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-gear"></i> სისტემის ინფორმაცია
            </h6>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">PHP ვერსია</span>
                <strong>{{ PHP_VERSION }}</strong>
            </div>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">სესიის ტიპი</span>
                <strong>{{ config('session.driver') }}</strong>
            </div>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">API URL</span>
                <strong class="text-break small">{{ config('services.partner_api.url') }}</strong>
            </div>
            
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">გარემო</span>
                <span class="badge bg-{{ app()->environment('production') ? 'success' : 'warning' }}">
                    {{ app()->environment() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-stars"></i> ფუნქციები
            </h6>
            
            <div class="d-flex align-items-center py-2 border-bottom">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>რეზერვაციების მართვა</span>
            </div>
            
            <div class="d-flex align-items-center py-2 border-bottom">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>რეალურ დროში შეტყობინებები</span>
            </div>
            
            <div class="d-flex align-items-center py-2 border-bottom">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>მაგიდების მართვა</span>
            </div>
            
            <div class="d-flex align-items-center py-2 border-bottom">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>გადახდების სისტემა</span>
            </div>
            
            <div class="d-flex align-items-center py-2 border-bottom">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>ანალიტიკა და რეპორტები</span>
            </div>
            
            <div class="d-flex align-items-center py-2">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span>გუნდის მართვა</span>
            </div>
        </div>
    </div>

    <!-- Developer Info -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-code-slash"></i> დეველოპერი
            </h6>
            
            <div class="text-center">
                <p class="mb-2"><strong>FOODLY Development Team</strong></p>
                <p class="text-muted small mb-2">
                    <i class="bi bi-envelope"></i> dev@foodlyapp.ge
                </p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-globe"></i> www.foodlyapp.ge
                </p>
            </div>
        </div>
    </div>

    <!-- Legal Links -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-bottom">
                <i class="bi bi-file-text text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">მომსახურების პირობები</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-bottom">
                <i class="bi bi-shield-lock text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">კონფიდენციალურობის პოლიტიკა</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2">
                <i class="bi bi-award text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">ლიცენზია</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center text-muted mb-4">
        <small>
            <i class="bi bi-c-circle"></i> 2025 FOODLY. ყველა უფლება დაცულია.<br>
            Made with <i class="bi bi-heart-fill text-danger"></i> in Georgia
        </small>
    </div>
</div>
@endsection
