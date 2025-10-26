@extends('mobile.layouts.app')

@section('title', 'პროფილის რედაქტირება - FOODLY')

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
            <i class="bi bi-person-circle text-primary"></i> პროფილის რედაქტირება
        </h4>
    </div>

    <!-- Profile Form -->
    <form action="{{ route('mobile.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Avatar Section -->
        <div class="card mb-3">
            <div class="card-body p-3 text-center">
                <div class="position-relative d-inline-block mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 100px; height: 100px; font-size: 48px;">
                        <i class="bi bi-person"></i>
                    </div>
                    <label for="avatar" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                           style="width: 36px; height: 36px; padding: 0;">
                        <i class="bi bi-camera"></i>
                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                    </label>
                </div>
                <p class="text-muted small mb-0">დააჭირეთ კამერის ხატულას ფოტოს შესაცვლელად</p>
            </div>
        </div>

        <!-- Personal Info -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="text-muted mb-3">პირადი ინფორმაცია</h6>
                
                <div class="mb-3">
                    <label for="first_name" class="form-label">
                        <i class="bi bi-person"></i> სახელი *
                    </label>
                    <input type="text" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('name', session('partner_user.name', '')) }}" 
                           required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">
                        <i class="bi bi-person"></i> გვარი *
                    </label>
                    <input type="text" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', session('partner_user.last_name', '')) }}" 
                           required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> ელფოსტა *
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', session('partner_user.email', '')) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label for="phone" class="form-label">
                        <i class="bi bi-telephone"></i> ტელეფონი
                    </label>
                    <div class="input-group">
                        <select class="form-select country-code-select" 
                                id="country_code" 
                                name="country_code" 
                                style="max-width: 120px;">
                            <option value="+995" data-flag="fi-ge" selected>🇬🇪 +995</option>
                            <option value="+1" data-flag="fi-us">🇺🇸 +1</option>
                            <option value="+44" data-flag="fi-gb">🇬🇧 +44</option>
                            <option value="+7" data-flag="fi-ru">🇷🇺 +7</option>
                            <option value="+90" data-flag="fi-tr">🇹🇷 +90</option>
                        </select>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', session('partner_user.phone', '')) }}" 
                               placeholder="5XX XX XX XX">
                    </div>
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>


        <!-- Language Preference -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="text-muted mb-3">ენის პარამეტრები</h6>
                
                <div class="mb-0">
                    <label for="language" class="form-label">
                        <i class="bi bi-translate"></i> ინტერფეისის ენა
                    </label>
                    <select class="form-select" id="language" name="language">
                        <option value="ka" selected>🇬🇪 ქართული</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="ru">🇷🇺 Русский</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> ცვლილებების შენახვა
            </button>
            <a href="{{ route('mobile.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> გაუქმება
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Preview avatar before upload
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview
                const preview = document.querySelector('.bg-primary.text-white.rounded-circle');
                preview.style.backgroundImage = `url(${e.target.result})`;
                preview.style.backgroundSize = 'cover';
                preview.innerHTML = '';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
