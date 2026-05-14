@extends('layouts.app')

@section('title', 'Payment Receipt')
@section('page-title', 'Payment Receipt')

@section('content')
<div class="card" id="receipt">
    <div class="card-body text-center">
        <div class="mb-4">
            <i class="fas fa-hotel fa-3x text-primary"></i>
            <h3 class="mt-2">Hostel Management System</h3>
            <p class="text-muted">Official Payment Receipt</p>
        </div>
        
        <div class="row mb-4">
            <div class="col-6 text-start">
                <strong>Receipt No:</strong> {{ $payment->receipt_number }}<br>
                <strong>Date:</strong> {{ date('d F Y', strtotime($payment->payment_date)) }}
            </div>
            <div class="col-6 text-end">
                <strong>Status:</strong> 
                <span class="badge bg-success">PAID</span>
            </div>
        </div>
        
        <table class="table table-bordered">
            <tr>
                <th width="40%">Student Name</th>
                <td class="text-start">{{ $payment->student->name }}</td>
            </tr>
            <tr>
                <th>Student ID</th>
                <td class="text-start">{{ $payment->student->student_id }}</td>
            </tr>
            <tr>
                <th>Room Number</th>
                <td class="text-start">{{ $payment->allocation->room->room_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Amount Paid</th>
                <td class="text-start text-success fw-bold">₦{{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td class="text-start">{{ ucfirst($payment->payment_method) }}</td>
            </tr>
            @if($payment->transaction_id)
            <tr>
                <th>Transaction ID</th>
                <td class="text-start">{{ $payment->transaction_id }}</td>
            </tr>
            @endif
            <tr>
                <th>Payment For</th>
                <td class="text-start">{{ $payment->month ?? 'N/A' }} {{ $payment->year ?? '' }}</td>
            </tr>
        </table>
        
        @if($payment->description)
        <div class="alert alert-info">
            <strong>Notes:</strong> {{ $payment->description }}
        </div>
        @endif
        
        <div class="mt-4">
            <p class="text-muted">Thank you for your payment!</p>
            <p class="text-muted small">This is a computer-generated receipt. No signature required.</p>
        </div>
        
        <hr>
        
        <div class="mt-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Receipt
            </button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .sidebar, footer, .card-header {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection