@extends('mobile.layouts.app')

@section('title', 'პაროლის შეცვლა - FOODLY')

@section('content')
<div class="container-fluid px-0">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('mobile.settings.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> უკან
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-3">
        <h4 class="mb-0">
            <i class="bi bi-shield-lock text-primary"></i> პაროლის შეცვლა
        </h4>
        <p class="text-muted small mb-0">უსაფრთხოების მიზნით რეკომენდებულია პაროლის რეგულარული შეცვლა</p>
    </div>

    <!-- Password Change Form -->
    <form action="{{ route('mobile.settings.password.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Current Password -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="text-muted mb-3">მიმდინარე პაროლი</h6>
                
                <div class="mb-0">
                    <label for="current_password" class="form-label">
                        <i class="bi bi-lock"></i> ძველი პაროლი *
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required
                               autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                            <i class="bi bi-eye" id="current_password_icon"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- New Password -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="text-muted mb-3">ახალი პაროლი</h6>
                
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-key"></i> ახალი პაროლი *
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               minlength="8"
                               autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                            <i class="bi bi-eye" id="password_icon"></i>
                        </button>
                    </div>
                    <small class="text-muted">მინიმუმ 8 სიმბოლო</small>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-key-fill"></i> გაიმეორეთ პაროლი *
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               minlength="8"
                               autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                            <i class="bi bi-eye" id="password_confirmation_icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">პაროლის სიძლიერე:</small>
                        <small id="strength_text" class="text-muted">-</small>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div id="strength_bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="card mb-3 border-info">
            <div class="card-body p-3">
                <h6 class="text-info mb-2">
                    <i class="bi bi-info-circle"></i> უსაფრთხოების რჩევები
                </h6>
                <ul class="small text-muted mb-0 ps-3">
                    <li>გამოიყენეთ მინიმუმ 8 სიმბოლო</li>
                    <li>შეიცავდეს დიდ და პატარა ასოებს</li>
                    <li>დაამატეთ ციფრები და სპეციალური სიმბოლოები</li>
                    <li>არ გამოიყენოთ პირადი ინფორმაცია</li>
                    <li>არ გაიმეოროთ ძველი პაროლები</li>
                </ul>
            </div>
        </div>

        <!-- Save Button -->
        <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> პაროლის შეცვლა
            </button>
            <a href="{{ route('mobile.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> გაუქმება
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Password strength checker
    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthBar = document.getElementById('strength_bar');
        const strengthText = document.getElementById('strength_text');
        
        let strength = 0;
        let text = 'ძალიან სუსტი';
        let color = 'bg-danger';
        
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 12.5;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 12.5;
        
        if (strength >= 87.5) {
            text = 'ძალიან ძლიერი';
            color = 'bg-success';
        } else if (strength >= 62.5) {
            text = 'ძლიერი';
            color = 'bg-info';
        } else if (strength >= 37.5) {
            text = 'საშუალო';
            color = 'bg-warning';
        } else if (strength >= 25) {
            text = 'სუსტი';
            color = 'bg-danger';
        }
        
        strengthBar.style.width = strength + '%';
        strengthBar.className = 'progress-bar ' + color;
        strengthText.textContent = text;
        strengthText.className = 'fw-semibold ' + color.replace('bg-', 'text-');
    });

    // Match password confirmation
    document.getElementById('password_confirmation').addEventListener('input', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = e.target.value;
        
        if (password !== confirmation && confirmation.length > 0) {
            e.target.classList.add('is-invalid');
        } else {
            e.target.classList.remove('is-invalid');
        }
    });
</script>
@endpush
@endsection
