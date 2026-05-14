@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Allocation Details</h3>
                    <a href="{{ route('allocations.index') }}" class="btn btn-secondary">← Back to List</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">Student Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Student Name</th>
                                    <td>{{ $allocation->student->name }}</td>
                                </tr>
                                <tr>
                                    <th>Student ID</th>
                                    <td>{{ $allocation->student->student_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $allocation->student->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Course</th>
                                    <td>{{ $allocation->student->course ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $allocation->student->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary">Room Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Room Number</th>
                                    <td>{{ $allocation->room->room_number }}</td>
                                </tr>
                                <tr>
                                    <th>Floor</th>
                                    <td>{{ $allocation->room->floor ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Capacity</th>
                                    <td>{{ $allocation->room->capacity }} beds</td>
                                </tr>
                                @if($allocation->room->price_per_month)
                                <tr>
                                    <th>Price/Month</th>
                                    <td>₦{{ number_format($allocation->room->price_per_month, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status</th>
                                    <td>{{ ucfirst($allocation->room->status ?? 'Available') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="text-primary">Allocation Details</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Allocation ID</th>
                            <td>{{ $allocation->id }}</td>
                        </tr>
                        <tr>
                            <th>Allocation Date</th>
                            <td>{{ $allocation->allocation_date->format('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ $allocation->end_date ? $allocation->end_date->format('l, d F Y') : 'Not ended yet' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($allocation->status == 'active')
                                    <span class="badge bg-success">✓ Active</span>
                                @elseif($allocation->status == 'completed')
                                    <span class="badge bg-info">✓ Completed</span>
                                @else
                                    <span class="badge bg-danger">✗ Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        @if($allocation->notes)
                        <tr>
                            <th>Notes</th>
                            <td>{{ $allocation->notes }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Created At</th>
                            <td>{{ $allocation->created_at->format('l, d F Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $allocation->updated_at->format('l, d F Y H:i:s') }}</td>
                        </tr>
                    </table>
                    
                    @if($allocation->status == 'active')
                        <div class="alert alert-info mt-3">
                            <strong>ℹ️ Information:</strong> This allocation is currently active. You can cancel or mark it as completed using the buttons below.
                        </div>
                        
                        <div class="btn-group mt-3" role="group">
                            <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-warning">✏️ Edit Allocation</a>
                            
                            <form action="{{ route('allocations.cancel', $allocation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this allocation?')">❌ Cancel Allocation</button>
                            </form>
                            
                            <form action="{{ route('allocations.complete', $allocation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" onclick="return confirm('Mark as completed?')">✅ Mark Complete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection