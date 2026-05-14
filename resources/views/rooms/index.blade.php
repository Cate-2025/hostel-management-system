@extends('layouts.app')

@section('title', 'Rooms')
@section('page-title', 'Room Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>All Rooms</h4>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Room
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room Number</th>
                        <th>Floor</th>
                        <th>Capacity</th>
                        <th>Occupied</th>
                        <th>Available</th>
                        <th>Occupancy %</th>
                        <th>Price/Month</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    @php
                        $occupiedCount = $room->occupied_count ?? 0;
                        $availableCount = $room->available_count ?? $room->capacity;
                        $percentage = $room->occupancy_percentage ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $room->id }}</td>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ $room->floor ?? '-' }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td><span class="badge bg-warning">{{ $occupiedCount }}</span></td>
                        <td><span class="badge bg-success">{{ $availableCount }}</span></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $percentage >= 80 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success') }}" 
                                     style="width: {{ $percentage }}%">
                                    {{ $percentage }}%
                                </div>
                            </div>
                        </td>
                        <td>₦{{ number_format($room->price_per_month ?? 0, 2) }}</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this room?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No rooms found. <a href="{{ route('rooms.create') }}">Add your first room</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection