@extends('layouts.app')

@section('title', 'Payment Report')
@section('page-title', 'Payment Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Payment Report</h4>
    </div>
    <div class="card-body">
        <!-- Currency Selector -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label>Select Currency</label>
                <select id="currency" class="form-control" onchange="updateCurrency()">
                    <option value="UGX">🇺🇬 Uganda Shillings (UGX)</option>
                    <option value="USD">🇺🇸 US Dollars (USD)</option>
                </select>
            </div>
        </div>
        
        <form method="GET" action="{{ route('reports.payments') }}" class="row mb-4">
            <div class="col-md-4">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? date('Y-m-01') }}">
            </div>
            <div class="col-md-4">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? date('Y-m-t') }}">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Filter</button>
            </div>
        </form>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong>Total Amount (UGX):</strong><br>
                    <h3>₦{{ number_format($totalAmount ?? 0, 2) }}</h3>
                    <small class="text-muted">Uganda Shillings</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <strong>Total Amount (USD):</strong><br>
                    <h3>${{ number_format(($totalAmount ?? 0) / 3800, 2) }}</h3>
                    <small class="text-muted">US Dollars (1 USD = 3,800 UGX)</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <strong>Exchange Rate:</strong><br>
                    <h4>1 USD = 3,800 UGX</h4>
                    <small class="text-muted">Current Rate</small>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-secondary">
                    <strong>Payment Methods Summary:</strong>
                    @if(isset($byMethod))
                        @foreach($byMethod as $method => $amount)
                            <span class="badge bg-primary me-2">
                                {{ ucfirst($method) }}: 
                                <span class="amount-ugx">₦{{ number_format($amount, 2) }}</span>
                                <span class="amount-usd" style="display: none;">${{ number_format($amount / 3800, 2) }}</span>
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Receipt #</th>
                        <th>Student</th>
                        <th>Amount (UGX)</th>
                        <th>Amount (USD)</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments ?? [] as $payment)
                    <tr>
                        <td>{{ $payment->receipt_number }}</td>
                        <td>{{ $payment->student->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $payment->student->student_id ?? '' }}</small>
                        </td>
                        <td class="amount-ugx">₦{{ number_format($payment->amount, 2) }}</td>
                        <td class="amount-usd" style="display: none;">${{ number_format($payment->amount / 3800, 2) }}</td>
                        <td>{{ date('d/m/Y', strtotime($payment->payment_date)) }}</td>
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
                            <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No payment records found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <th colspan="2" class="text-end"><strong>Total:</strong></th>
                    <th class="amount-ugx"><strong>₦{{ number_format($payments->sum('amount') ?? 0, 2) }}</strong></th>
                    <th class="amount-usd" style="display: none;"><strong>${{ number_format(($payments->sum('amount') ?? 0) / 3800, 2) }}</strong></th>
                    <th colspan="3"></th>
                </tfoot>
            </table>
        </div>
        
        <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back to Reports</a>
    </div>
</div>

<script>
function updateCurrency() {
    var currency = document.getElementById('currency').value;
    var ugxElements = document.querySelectorAll('.amount-ugx');
    var usdElements = document.querySelectorAll('.amount-usd');
    
    if (currency === 'USD') {
        ugxElements.forEach(function(el) {
            el.style.display = 'none';
        });
        usdElements.forEach(function(el) {
            el.style.display = 'table-cell';
        });
    } else {
        ugxElements.forEach(function(el) {
            el.style.display = 'table-cell';
        });
        usdElements.forEach(function(el) {
            el.style.display = 'none';
        });
    }
}
</script>
@endsection