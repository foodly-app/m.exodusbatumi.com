@extends('mobile.layouts.app')

@section('title', 'პროფილის რედაქტირება')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> ჩემი პროფილი</h5>
                </div>
                <div class="card-body">
                    @if($error)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> {{ $error }}
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
                                       value="{{ old('name', $user['name'] ?? '') }}" 
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
                                       value="{{ $user['email'] ?? '' }}" 
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
                                       value="{{ old('phone', $user['phone'] ?? '') }}" 
                                       placeholder="+995 5XX XX XX XX">
                            </div>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> ცვლილებების შენახვა
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">

                    <!-- Password Change Link -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('mobile.settings.password') }}" class="btn btn-outline-primary">
                            <i class="bi bi-key"></i> პაროლის შეცვლა
                        </a>
                        <a href="{{ route('mobile.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> მთავარი გვერდი
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
