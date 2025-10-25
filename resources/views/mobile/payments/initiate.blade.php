@extends('mobile.layouts.app')

@section('title', 'გადახდის ინიცირება')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-credit-card"></i> გადახდა</h4>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="text-primary">რეზერვაციის დეტალები</h5>
                    <p><strong>რესტორანი:</strong> {{ $reservation->reservable->name ?? 'N/A' }}</p>
                    <p><strong>თარიღი:</strong> {{ $reservation->reservation_date }}</p>
                    <p><strong>დრო:</strong> {{ substr($reservation->time_from, 0, 5) }}</p>
                    <p><strong>სტუმრები:</strong> {{ $reservation->guests_count }}</p>
                </div>

                <hr>

                <div class="mb-4">
                    <h5 class="text-primary">გადასახდელი თანხა</h5>
                    <h2 class="display-4 text-center text-success">{{ $reservation->total_price }} GEL</h2>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>გადახდის პირობები:</strong>
                    <ul class="mb-0 mt-2">
                        <li>გადახდის შემდეგ მიიღებთ დადასტურებას</li>
                        <li>გადახდა უსაფრთხოა BOG-ის სისტემით</li>
                        <li>გადახდის ვადა: 15 წუთი</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('mobile.payments.process', $reservation->id) }}">
                    @csrf
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card"></i> გადახდის გვერდზე გადასვლა
                        </button>
                        <a href="{{ route('mobile.reservations.show', $reservation->id) }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> უკან
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
