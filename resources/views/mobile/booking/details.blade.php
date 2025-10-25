@extends('mobile.layouts.app')

@section('title', 'რეზერვაციის დეტალები')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> შეავსეთ დეტალები
                </h4>
                <p class="mb-0 mt-2 text-white-50">
                    {{ $restaurant->name }} - {{ $date }} {{ $time }}
                </p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('mobile.booking.create', $restaurant->slug) }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="time" value="{{ $time }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">სახელი და გვარი *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', session('partner_user.name')) }}" 
                                       required>
                            </div>
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">ტელეფონი *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', session('partner_user.phone')) }}" 
                                       required>
                            </div>
                            @error('phone')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">ელ. ფოსტა</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', session('partner_user.email')) }}">
                            </div>
                            @error('email')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="guests_count" class="form-label">სტუმრების რაოდენობა *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <select class="form-select @error('guests_count') is-invalid @enderror" 
                                        id="guests_count" 
                                        name="guests_count" 
                                        required>
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ old('guests_count', 2) == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'სტუმარი' : 'სტუმარი' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @error('guests_count')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">შენიშვნები (არასავალდებულო)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="მაგ: ფანჯრის მახლობელი მაგიდა...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>რეზერვაციის დეტალები:</strong><br>
                        რესტორანი: <strong>{{ $restaurant->name }}</strong><br>
                        თარიღი: <strong>{{ $date }}</strong><br>
                        დრო: <strong>{{ $time }}</strong>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> ჯავშნის დადასტურება
                        </button>
                        <a href="{{ route('mobile.booking.restaurants') }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> გაუქმება
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
