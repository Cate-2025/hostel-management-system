@extends('layouts.app')

@section('title', 'Financial Report')
@section('page-title', 'Financial Report')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Revenue</h6>
                <h3>₦{{ number_format($totalRevenue ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Transactions</h6>
                <h3>{{ $totalTransactions ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Average Payment</h6>
                <h3>₦{{ number_format($averagePayment ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>Monthly Financial Summary - {{ $year ?? date('Y') }}</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.financial') }}" class="row mb-4">
            <div class="col-md-4">
                <label>Select Year</label>
                <select name="year" class="form-control">
                    @for($y = 2024; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ ($year ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Filter</button>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Number of Payments</th>
                        <th>Total Amount</th>
                        <th>Average Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyData ?? [] as $data)
                    <tr>
                        <td><strong>{{ $data['month'] }}</strong></td>
                        <td>{{ $data['count'] }} payments</td>
                        <td>₦{{ number_format($data['amount'], 2) }}</td>
                        <td>₦{{ number_format($data['count'] > 0 ? $data['amount'] / $data['count'] : 0, 2) }}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No financial data found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <th>Total</th>
                    <th>{{ array_sum(array_column($monthlyData ?? [], 'count')) }}</th>
                    <th>₦{{ number_format(array_sum(array_column($monthlyData ?? [], 'amount')), 2) }}</th>
                    <th>-</th>
                </tfoot>
            </table>
        </div>
        
        <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back to Reports</a>
    </div>
</div>
@endsection