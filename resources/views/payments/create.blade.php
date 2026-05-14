@extends('layouts.app')

@section('title', 'Record Payment')
@section('page-title', 'Record New Payment')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
                    @csrf
                    <div class="mb-3">
                        <label>Select Student *</label>
                        <select name="student_id" id="student_id" class="form-control" required>
                            <option value="">-- Select Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->student_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="allocationInfo" class="alert alert-info mb-3" style="display: none;">
                        <strong>Room Details:</strong>
                        <span id="roomInfo"></span>
                        <br>
                        <strong>Monthly Rent (UGX):</strong> UGX <span id="roomPriceUGX">0</span>
                        <br>
                        <strong>Monthly Rent (USD):</strong> $<span id="roomPriceUSD">0</span>
                        <br>
                        <strong>Total Paid (UGX):</strong> UGX <span id="totalPaidUGX">0</span>
                        <br>
                        <strong>Total Paid (USD):</strong> $<span id="totalPaidUSD">0</span>
                        <br>
                        <strong>Outstanding Balance (UGX):</strong> UGX <span id="balanceUGX">0</span>
                        <br>
                        <strong>Outstanding Balance (USD):</strong> $<span id="balanceUSD">0</span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Currency *</label>
                            <select name="currency" id="currency" class="form-control" required>
                                <option value="UGX">🇺🇬 Uganda Shillings (UGX)</option>
                                <option value="USD">🇺🇸 US Dollars (USD)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Amount *</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                            <small id="amountHint" class="text-muted">Enter amount in UGX</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Payment Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="card">Card Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Transaction ID (Optional)</label>
                            <input type="text" name="transaction_id" class="form-control" placeholder="Reference number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Payment For (Month)</label>
                            <select name="month" class="form-control">
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Year</label>
                            <input type="text" name="year" class="form-control" value="{{ date('Y') }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Payment notes..."></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5><i class="fas fa-info-circle"></i> Payment Guidelines</h5>
                <hr>
                <ul class="small">
                    <li>Exchange Rate: <strong>1 USD = 3,800 UGX</strong></li>
                    <li>Only students with active allocations can make payments</li>
                    <li>Receipt number is auto-generated</li>
                    <li>All payments are stored in UGX</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('student_id').addEventListener('change', function() {
    let studentId = this.value;
    if (studentId) {
        fetch(`/payments/student/${studentId}/balance`)
            .then(response => response.json())
            .then(data => {
                if (data.has_allocation) {
                    document.getElementById('allocationInfo').style.display = 'block';
                    document.getElementById('roomInfo').innerHTML = `Room ${data.room_number}`;
                    document.getElementById('roomPriceUGX').innerHTML = data.room_price_ugx.toLocaleString();
                    document.getElementById('roomPriceUSD').innerHTML = data.room_price_usd;
                    document.getElementById('totalPaidUGX').innerHTML = data.total_paid_ugx.toLocaleString();
                    document.getElementById('totalPaidUSD').innerHTML = data.total_paid_usd;
                    document.getElementById('balanceUGX').innerHTML = data.balance_ugx.toLocaleString();
                    document.getElementById('balanceUSD').innerHTML = data.balance_usd;
                } else {
                    document.getElementById('allocationInfo').style.display = 'none';
                    alert('This student has no active room allocation!');
                }
            });
    }
});

document.getElementById('currency').addEventListener('change', function() {
    var currency = this.value;
    var amountHint = document.getElementById('amountHint');
    var amountField = document.getElementById('amount');
    
    if (currency === 'USD') {
        amountHint.innerHTML = 'Enter amount in USD (will be converted to UGX)';
        amountField.placeholder = 'Enter USD amount';
    } else {
        amountHint.innerHTML = 'Enter amount in UGX';
        amountField.placeholder = 'Enter UGX amount';
    }
});
</script>
@endsection