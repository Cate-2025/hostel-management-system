@extends('layouts.app')

@section('title', 'Reports Dashboard')
@section('page-title', 'Reports & Analytics Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <i class="fas fa-building fa-2x float-end"></i>
                <h6>Total Rooms</h6>
                <h3>{{ $totalRooms ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <i class="fas fa-users fa-2x float-end"></i>
                <h6>Total Students</h6>
                <h3>{{ $totalStudents ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <i class="fas fa-bed fa-2x float-end"></i>
                <h6>Active Allocations</h6>
                <h3>{{ $activeAllocations ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x float-end"></i>
                <h6>Total Revenue</h6>
                <h3>₦{{ number_format($totalRevenue ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Monthly Revenue - {{ date('Y') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Occupancy Rate</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-1">{{ $occupancyRate ?? 0 }}%</h1>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ $occupancyRate ?? 0 }}%"></div>
                </div>
                <p class="mt-3">{{ $occupiedRooms ?? 0 }} out of {{ $totalRoomsCount ?? 0 }} rooms occupied</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Payments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments ?? [] as $payment)
                            <tr>
                                <td>{{ $payment->student->name ?? 'N/A' }}</td>
                                <td>₦{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ date('d M', strtotime($payment->payment_date)) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No payments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Allocations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Room</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAllocations ?? [] as $allocation)
                            <tr>
                                <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                                <td>Room {{ $allocation->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $allocation->allocation_date ? $allocation->allocation_date->format('d M') : 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No allocations found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Quick Report Links</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('reports.payments') }}" class="btn btn-outline-primary m-1">
                    <i class="fas fa-money-bill"></i> Payment Report
                </a>
                <a href="{{ route('reports.allocations') }}" class="btn btn-outline-success m-1">
                    <i class="fas fa-bed"></i> Allocation Report
                </a>
                <a href="{{ route('reports.occupancy') }}" class="btn btn-outline-info m-1">
                    <i class="fas fa-chart-pie"></i> Occupancy Report
                </a>
                <a href="{{ route('reports.financial') }}" class="btn btn-outline-warning m-1">
                    <i class="fas fa-chart-line"></i> Financial Report
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var revenueChartCanvas = document.getElementById('revenueChart');
    
    if (revenueChartCanvas) {
        var revenueData = {{ json_encode($revenueData ?? array_fill(0, 12, 0)) }};
        var monthsData = {{ json_encode($months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) }};
        
        new Chart(revenueChartCanvas, {
            type: 'line',
            data: {
                labels: monthsData,
                datasets: [{
                    label: 'Revenue (₦)',
                    data: revenueData,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₦' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection