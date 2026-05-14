@extends('layouts.app')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Payment Information</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Receipt Number</th>
                        <td><strong>{{ $payment->receipt_number }}</strong></td>
                    </tr>
                    <tr>
                        <th>Student Name</th>
                        <td>{{ $payment->student->name }} ({{ $payment->student->student_id }})</td>
                    </tr>
                    <tr>
                        <th>Room Number</th>
                        <td>{{ $payment->allocation->room->room_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Amount Paid</th>
                        <td><strong class="text-success">₦{{ number_format($payment->amount, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Payment Date</th>
                        <td>{{ date('d F Y', strtotime($payment->payment_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($payment->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-warning">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @if($payment->description)
        <div class="row">
            <div class="col-12">
                <strong>Description:</strong>
                <p>{{ $payment->description }}</p>
            </div>
        </div>
        @endif
        <div class="mt-3">
            <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-primary">Print Receipt</a>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection