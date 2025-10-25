@extends('mobile.layouts.app')

@section('title', 'გადახდა ვერ შესრულდა')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-danger">
            <div class="card-body text-center p-5">
                <div class="text-danger mb-4">
                    <i class="bi bi-x-circle display-1"></i>
                </div>
                <h2 class="text-danger mb-3">გადახდა ვერ შესრულდა</h2>
                <p class="lead mb-4">თქვენი გადახდა არ დასრულებულა წარმატებით</p>

                <div class="alert alert-danger text-start">
                    <h6><i class="bi bi-exclamation-triangle"></i> შეცდომის დეტალები:</h6>
                    <p class="mb-1"><strong>თანხა:</strong> {{ $payment->amount }} {{ $payment->currency }}</p>
                    <p class="mb-1"><strong>სტატუსი:</strong> <span class="badge bg-danger">{{ $payment->status }}</span></p>
                    <p class="mb-0"><strong>თარიღი:</strong> {{ $payment->created_at->format('d.m.Y H:i') }}</p>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('mobile.payments.initiate', $payment->reservation_id) }}" 
                       class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-clockwise"></i> ხელახალი მცდელობა
                    </a>
                    <a href="{{ route('mobile.reservations.show', $payment->reservation_id) }}" 
                       class="btn btn-outline-secondary">
                        <i class="bi bi-eye"></i> რეზერვაციის ნახვა
                    </a>
                    <a href="{{ route('mobile.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> მთავარ გვერდზე
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
