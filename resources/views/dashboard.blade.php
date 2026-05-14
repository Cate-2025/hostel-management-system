@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success">
            <h4>Welcome back, {{ Auth::user()->name }}!</h4>
            <p>You are logged into the Hostel Management System.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Total Rooms</h5>
                <h2>{{ $totalRooms ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Total Students</h5>
                <h2>{{ $totalStudents ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5>Active Allocations</h5>
                <h2>{{ $activeAllocations ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Recent Allocations</h5>
            </div>
            <div class="card-body">
                @if(isset($recentAllocations) && count($recentAllocations) > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Room</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAllocations as $allocation)
                            <tr>
                                <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                                <td>Room {{ $allocation->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $allocation->allocation_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($allocation->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center">No recent allocations found.</p>
                @endif
                <a href="{{ route('allocations.create') }}" class="btn btn-primary mt-2">New Allocation</a>
            </div>
        </div>
    </div>
</div>
@endsection