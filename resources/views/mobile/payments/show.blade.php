@extends('mobile.layouts.app')

@section('title', 'გადახდის დეტალები')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-receipt"></i> გადახდის დეტალები</h4>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <h6 class="text-primary">სტატუსი</h6>
                    <span class="badge bg-{{ $payment->status }} fs-5">{{ $payment->status }}</span>
                </div>

                <div class="mb-4">
                    <h6 class="text-primary">თანხა</h6>
                    <h3>{{ $payment->amount }} {{ $payment->currency }}</h3>
                </div>

                <div class="mb-4">
                    <h6 class="text-primary">გადახდის მეთოდი</h6>
                    <p class="mb-0">{{ strtoupper($payment->payment_method) }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="text-primary">თარიღი</h6>
                    <p class="mb-0">{{ $payment->created_at->format('d.m.Y H:i:s') }}</p>
                </div>

                @if($payment->bog_payment_id)
                <div class="mb-4">
                    <h6 class="text-primary">გადახდის ID</h6>
                    <p class="mb-0"><code>{{ $payment->bog_payment_id }}</code></p>
                </div>
                @endif

                <hr>

                <div class="mb-4">
                    <h6 class="text-primary">რეზერვაცია</h6>
                    <p><strong>რესტორანი:</strong> {{ $payment->reservation->reservable->name ?? 'N/A' }}</p>
                    <p><strong>თარიღი:</strong> {{ $payment->reservation->reservation_date }}</p>
                    <p><strong>დრო:</strong> {{ substr($payment->reservation->time_from, 0, 5) }}</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('mobile.reservations.show', $payment->reservation_id) }}" 
                       class="btn btn-primary">
                        <i class="bi bi-eye"></i> რეზერვაციის ნახვა
                    </a>
                    <a href="{{ route('mobile.payments.history') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> უკან
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
