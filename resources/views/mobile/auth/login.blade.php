@extends('mobile.layouts.app')

@section('title', 'შესვლა - Foodly')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> შესვლა</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('mobile.login') }}">
                    @csrf

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
                                   autofocus
                                   placeholder="example@email.com">
                        </div>
                        @error('email')
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
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            დამახსოვრება
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> შესვლა
                        </button>
                    </div>
                </form>
            </div>
            <!-- Registration removed as requested -->
        </div>
    </div>
</div>
@endsection