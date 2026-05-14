@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Allocation #{{ $allocation->id }}</h3>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <strong>Current Status:</strong> 
                        <span class="badge bg-success">{{ ucfirst($allocation->status) }}</span>
                    </div>

                    <form action="{{ route('allocations.update', $allocation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $allocation->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->student_id ?? 'No ID' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">-- Choose Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id', $allocation->room_id) == $room->id ? 'selected' : '' }}>
                                        Room {{ $room->room_number }} - Floor {{ $room->floor ?? 'N/A' }}
                                        ({{ $room->available_beds }} of {{ $room->capacity }} beds available)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Note: Only rooms with available beds are shown, plus the currently allocated room.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                            <input type="date" name="allocation_date" id="allocation_date" 
                                   class="form-control" value="{{ old('allocation_date', $allocation->allocation_date->format('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $allocation->notes) }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">💾 Update Allocation</button>
                            <a href="{{ route('allocations.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh available rooms periodically
    function refreshAvailableRooms() {
        fetch('/available-rooms')
            .then(response => response.json())
            .then(data => {
                const roomSelect = document.getElementById('room_id');
                const currentRoomId = '{{ $allocation->room_id }}';
                
                if (data.success && roomSelect) {
                    const currentValue = roomSelect.value;
                    roomSelect.innerHTML = '<option value="">-- Choose Room --</option>';
                    
                    // Add current room first
                    data.rooms.forEach(room => {
                        if (room.id == currentRoomId) {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = `Room ${room.room_number} (Current - ${room.available_beds} beds available)`;
                            if (currentValue == room.id) {
                                option.selected = true;
                            }
                            roomSelect.appendChild(option);
                        }
                    });
                    
                    // Add other available rooms
                    data.rooms.forEach(room => {
                        if (room.id != currentRoomId) {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = `Room ${room.room_number} - ${room.available_beds} of ${room.total_capacity} beds available`;
                            if (currentValue == room.id) {
                                option.selected = true;
                            }
                            roomSelect.appendChild(option);
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    refreshAvailableRooms();
});
</script>
@endpush