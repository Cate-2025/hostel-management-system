@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Room Allocations</h3>
                    <a href="{{ route('allocations.create') }}" class="btn btn-primary">+ New Allocation</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <th>Room Number</th>
                                    <th>Allocation Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $allocation)
                                <tr>
                                    <td>{{ $allocation->id }}</td>
                                    <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                                    <td>{{ $allocation->student->student_id ?? 'N/A' }}</td>
                                    <td>{{ $allocation->room->room_number ?? 'N/A' }}</td>
                                    <td>{{ $allocation->allocation_date ? $allocation->allocation_date->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $allocation->end_date ? $allocation->end_date->format('d/m/Y') : 'Active' }}</td>
                                    <td>
                                        @if($allocation->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($allocation->status == 'completed')
                                            <span class="badge bg-info">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-sm btn-info">View</a>
                                            
                                            @if($allocation->status == 'active')
                                                <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-sm btn-warning">Edit</a>
                                                
                                                <form action="{{ route('allocations.cancel', $allocation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this allocation?')">Cancel</button>
                                                </form>
                                                
                                                <form action="{{ route('allocations.complete', $allocation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this allocation as completed?')">Complete</button>
                                                </form>
                                            @endif
                                            
                                            @if($allocation->status != 'active')
                                                <form action="{{ route('allocations.destroy', $allocation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('Delete this allocation permanently?')">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No allocations found. Click "New Allocation" to create one.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $allocations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection