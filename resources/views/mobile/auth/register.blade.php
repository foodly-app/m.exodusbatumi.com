@extends('mobile.layouts.app')

@section('title', 'რეგისტრაცია - Foodly')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> რეგისტრაცია</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('mobile.register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">სახელი და გვარი</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus
                                   placeholder="გიორგი გიორგაძე">
                        </div>
                        @error('name')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">ელ. ფოსტა</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   placeholder="example@email.com">
                        </div>
                        @error('email')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">ტელეფონი (არასავალდებულო)</label>
                        <div class="input-group">
                            <select class="form-select country-code-select" id="country_code" name="country_code">
                                @foreach(config('countries.phone_codes') as $country)
                                    <option value="{{ $country['code'] }}" 
                                            data-flag="fi-{{ $country['iso'] }}"
                                            {{ old('country_code', config('countries.default_code')) == $country['code'] ? 'selected' : '' }}>
                                        {{ $country['code'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="555 123 456">
                        </div>
                        @error('phone')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                        @error('country_code')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">პაროლი</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="მინიმუმ 8 სიმბოლო">
                        </div>
                        @error('password')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">პაროლის დადასტურება</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="გაიმეორეთ პაროლი">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-plus"></i> რეგისტრაცია
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center bg-light">
                <p class="mb-0">უკვე გაქვთ ანგარიში? 
                    <a href="{{ route('mobile.login') }}" class="text-decoration-none fw-bold">
                        შესვლა
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Simple flag display with CSS
document.addEventListener('DOMContentLoaded', function() {
    const select = document.querySelector('.country-code-select');
    if (!select) return;
    
    // Add flag icon to each option using ::before with CSS
    select.querySelectorAll('option').forEach(option => {
        const flag = option.dataset.flag;
        if (flag) {
            option.textContent = `${option.textContent.trim()}`;
        }
    });
});
</script>
@endpush

@endsection