// Auto-refresh available rooms when student is selected
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const roomSelect = document.getElementById('room_id');
    
    if (studentSelect) {
        studentSelect.addEventListener('change', function() {
            fetchAvailableRooms();
        });
    }
    
    function fetchAvailableRooms() {
        fetch('/available-rooms')
            .then(response => response.json())
            .then(data => {
                if (data.success && roomSelect) {
                    // Clear current options
                    roomSelect.innerHTML = '<option value="">-- Choose Room --</option>';
                    
                    // Add new options
                    data.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `Room ${room.room_number} - Floor ${room.floor} (${room.available_beds} of ${room.total_capacity} beds available)`;
                        roomSelect.appendChild(option);
                    });
                    
                    // Show message if no rooms available
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
    
    // Initial load of available rooms
    if (roomSelect && roomSelect.options.length <= 1) {
        fetchAvailableRooms();
    }
});