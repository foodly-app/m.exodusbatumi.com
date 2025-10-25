@extends('mobile.layouts.app')

@section('title', 'პროფილის რედაქტირება')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-person-circle"></i> ჩემი პროფილი</h4>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('mobile.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">სახელი და გვარი</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', Auth::user()->name) }}" 
                                   required>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">ელ-ფოსტა</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   value="{{ Auth::user()->email }}" 
                                   disabled>
                        </div>
                        <small class="text-muted">ელ-ფოსტის შეცვლა შეუძლებელია</small>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">ტელეფონის ნომერი</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', Auth::user()->phone) }}" 
                                   placeholder="+995 5XX XX XX XX">
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h6 class="text-primary mb-3">პაროლის შეცვლა</h6>
                    <p class="text-muted small">თუ არ გსურთ პაროლის შეცვლა, დატოვეთ ცარიელი</p>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">მიმდინარე პაროლი</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password">
                        </div>
                        @error('current_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">ახალი პაროლი</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password">
                        </div>
                        @error('new_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label">პაროლის დადასტურება</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> ცვლილებების შენახვა
                        </button>
                        <a href="{{ route('mobile.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> უკან
                        </a>
                    </div>
                </form>

                <!-- Logout Section -->
                <hr class="my-4">
                <form method="POST" action="{{ route('mobile.logout') }}">
                    @csrf
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-danger btn-lg">
                            <i class="bi bi-box-arrow-right"></i> გასვლა
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
