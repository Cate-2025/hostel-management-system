@extends('layouts.app')

@section('title', 'Occupancy Report')
@section('page-title', 'Room Occupancy Report')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Capacity</h6>
                <h3>{{ $totalCapacity ?? 0 }} beds</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Occupied Beds</h6>
                <h3>{{ $totalOccupied ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Overall Occupancy</h6>
                <h3>{{ $overallPercentage ?? 0 }}%</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>Room-by-Room Occupancy Details</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Capacity</th>
                        <th>Occupied Beds</th>
                        <th>Available Beds</th>
                        <th>Occupancy Rate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($occupancyData ?? [] as $room)
                    <tr>
                        <td>Room {{ $room['room_number'] }}</td>
                        <td>{{ $room['capacity'] }}</td>
                        <td>{{ $room['occupied'] }}</td>
                        <td>{{ $room['available'] }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $room['percentage'] >= 80 ? 'danger' : ($room['percentage'] >= 50 ? 'warning' : 'success') }}" 
                                     style="width: {{ $room['percentage'] }}%">
                                    {{ $room['percentage'] }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $room['percentage'] >= 100 ? 'danger' : ($room['percentage'] > 0 ? 'warning' : 'success') }}">
                                @if($room['percentage'] >= 100)
                                    Full
                                @elseif($room['percentage'] > 0)
                                    Partially Occupied
                                @else
                                    Vacant
                                @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No room records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back to Reports</a>
    </div>
</div>
@endsection