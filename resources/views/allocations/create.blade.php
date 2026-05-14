@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Create New Room Allocation</h3>
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

                    <form action="{{ route('allocations.store') }}" method="POST" id="allocationForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->student_id ?? 'No ID' }}) - {{ $student->course ?? 'No Course' }}
                                    </option>
                                @endforeach
                            </select>
                            @if($students->isEmpty())
                                <small class="text-warning">⚠️ All students already have active allocations.</small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Select Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">-- Choose Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        Room {{ $room->room_number }} - Floor {{ $room->floor ?? 'N/A' }} 
                                        ({{ $room->available_beds }} of {{ $room->capacity }} beds available)
                                        @if($room->price_per_month) - ₦{{ number_format($room->price_per_month, 2) }}/month @endif
                                    </option>
                                @endforeach
                            </select>
                            @if($rooms->isEmpty())
                                <small class="text-warning">⚠️ No rooms with available beds.</small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                            <input type="date" name="allocation_date" id="allocation_date" 
                                   class="form-control" value="{{ old('allocation_date', date('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="Any special notes about this allocation...">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">✅ Allocate Room</button>
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
    const form = document.getElementById('allocationForm');
    const roomSelect = document.getElementById('room_id');
    
    // Function to refresh available rooms
    function refreshAvailableRooms() {
        fetch('/available-rooms')
            .then(response => response.json())
            .then(data => {
                if (data.success && roomSelect) {
                    const currentValue = roomSelect.value;
                    roomSelect.innerHTML = '<option value="">-- Choose Room --</option>';
                    
                    data.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `Room ${room.room_number} - Floor ${room.floor} (${room.available_beds} of ${room.total_capacity} beds available)`;
                        if (currentValue == room.id) {
                            option.selected = true;
                        }
                        roomSelect.appendChild(option);
                    });
                    
                    if (data.rooms.length === 0) {
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'No rooms available at the moment';
                        roomSelect.appendChild(option);
                    }
                }
            })
            .catch(error => console.error('Error fetching rooms:', error));
    }
    
    // Refresh rooms every 30 seconds
    refreshAvailableRooms();
    setInterval(refreshAvailableRooms, 30000);
    
    // Validate before submit
    if (form) {
        form.addEventListener('submit', function(e) {
            const roomId = roomSelect.value;
            if (!roomId) {
                e.preventDefault();
                alert('Please select a room');
                return false;
            }
        });
    }
});
</script>
@endpush