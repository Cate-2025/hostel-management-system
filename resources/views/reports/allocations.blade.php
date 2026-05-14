@extends('layouts.app')

@section('title', 'Allocation Report')
@section('page-title', 'Allocation Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Room Allocation Report</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.allocations') }}" class="row mb-4">
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ ($status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Room Number</th>
                        <th>Allocation Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allocations ?? [] as $allocation)
                    <tr>
                        <td>{{ $allocation->id }}</td>
                        <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                        <td>{{ $allocation->student->student_id ?? 'N/A' }}</td>
                        <td>Room {{ $allocation->room->room_number ?? 'N/A' }}</td>
                        <td>{{ date('d/m/Y', strtotime($allocation->allocation_date)) }}</td>
                        <td>{{ $allocation->end_date ? date('d/m/Y', strtotime($allocation->end_date)) : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $allocation->status == 'active' ? 'success' : ($allocation->status == 'completed' ? 'info' : 'danger') }}">
                                {{ ucfirst($allocation->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No allocation records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back to Reports</a>
    </div>
</div>
@endsection