@extends('mobile.layouts.app')

@section('title', 'შესვლა - FOODLY')

@push('styles')
<style>
    /* Remove header and bottom nav spacing for login page */
    body {
        padding-bottom: 0 !important;
    }
    .main-content {
        margin-top: 0 !important;
        padding: 0 !important;
        min-height: 100vh !important;
        height: 100vh !important;
        overflow: hidden !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .mobile-header,
    .bottom-nav {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid h-100 d-flex align-items-center justify-content-center px-3">
    <div class="w-100" style="max-width: 400px;">
        <!-- Logo Section -->
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('images/logo/SVG/System Shock Blue/System Shock Blue-01.svg') }}" alt="FOODLY" style="height: 50px;">
            </div>
            <h5 class="fw-bold mb-2" style="color: var(--foodly-primary);">კეთილი იყოს თქვენი დაბრუნება!</h5>
            <p class="text-muted small mb-0">შედით თქვენს ანგარიშში</p>
        </div>

        <!-- Login Card -->
        <div class="card border-0 shadow-lg">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('mobile.login') }}">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">
                            <i class="bi bi-envelope text-primary me-1"></i>ელ. ფოსტა
                        </label>
                        <input type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               placeholder="example@email.com">
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold small">
                            <i class="bi bi-lock text-primary me-1"></i>პაროლი
                        </label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="••••••••">
                            <button type="button" 
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted p-0 pe-3"
                                    id="togglePassword"
                                    style="text-decoration: none; z-index: 10;">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small" for="remember">
                                დამახსოვრება
                            </label>
                        </div>
                        <a href="#" class="text-primary text-decoration-none small">
                            <i class="bi bi-question-circle"></i> დაგავიწყდათ?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>შესვლა
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-3">
            <p class="text-muted small mb-0">
                © 2025 FOODLY
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });
</script>
@endpush
@endsection