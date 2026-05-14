@extends('layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payment Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x float-end"></i>
                <h5>Total Revenue</h5>
                <h2>₦{{ number_format($totalRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <i class="fas fa-clock fa-3x float-end"></i>
                <h5>Pending Amount</h5>
                <h2>₦{{ number_format($pendingAmount, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <i class="fas fa-receipt fa-3x float-end"></i>
                <h5>Total Transactions</h5>
                <h2>{{ $totalPayments }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Payment History</h4>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Payment
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td><strong>{{ $payment->receipt_number }}</strong></td>
                        <td>{{ $payment->student->name }}<br>
                            <small class="text-muted">{{ $payment->student->student_id }}</small>
                        </td>
                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ date('d M Y', strtotime($payment->payment_date)) }}</td>
                        <td>
                            @if($payment->payment_method == 'cash')
                                <span class="badge bg-success">Cash</span>
                            @elseif($payment->payment_method == 'bank_transfer')
                                <span class="badge bg-info">Bank Transfer</span>
                            @else
                                <span class="badge bg-primary">Card</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($payment->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-secondary">Receipt</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No payments recorded yet. <a href="{{ route('payments.create') }}">Record first payment</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection