@extends('mobile.layouts.app')

@section('title', 'გადახდების ისტორია')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-credit-card"></i> გადახდების ისტორია</h4>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>თარიღი</th>
                                    <th>რესტორანი</th>
                                    <th>თანხა</th>
                                    <th>სტატუსი</th>
                                    <th>მოქმედება</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        {{ $payment->reservation->reservable->name ?? 'N/A' }}<br>
                                        <small class="text-muted">{{ $payment->reservation->reservation_date }}</small>
                                    </td>
                                    <td class="fw-bold">{{ $payment->amount }} {{ $payment->currency }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('mobile.payments.show', $payment->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> დეტალები
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-credit-card display-1"></i>
                        <p class="mt-3">გადახდების ისტორია ცარიელია</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
