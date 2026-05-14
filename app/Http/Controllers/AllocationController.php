<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllocationController extends Controller
{
    // Remove the constructor with middleware - middleware should be in routes
    
    public function index()
    {
        $allocations = Allocation::with(['student', 'room'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('allocations.index', compact('allocations'));
    }
    
    public function create()
    {
        // Get students without active allocations
        $students = Student::whereDoesntHave('allocations', function($query) {
            $query->where('status', 'active');
        })->get();
        
        // Get rooms with available beds
        $allRooms = Room::all();
        $availableRooms = collect();
        
        foreach($allRooms as $room) {
            $occupiedCount = Allocation::where('room_id', $room->id)
                ->where('status', 'active')
                ->count();
            
            $availableBeds = $room->capacity - $occupiedCount;
            
            if ($availableBeds > 0) {
                $room->available_beds = $availableBeds;
                $availableRooms->push($room);
            }
        }
        
        return view('allocations.create', compact('students', 'availableRooms'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'allocation_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        // Check if student already has active allocation
        $hasActiveAllocation = Allocation::where('student_id', $request->student_id)
            ->where('status', 'active')
            ->exists();
            
        if ($hasActiveAllocation) {
            return back()->with('error', 'This student already has an active allocation!');
        }
        
        // Check if room has capacity
        $occupiedCount = Allocation::where('room_id', $request->room_id)
            ->where('status', 'active')
            ->count();
        
        $room = Room::find($request->room_id);
        
        if ($occupiedCount >= $room->capacity) {
            return back()->with('error', 'This room is already full!');
        }
        
        // Create allocation
        Allocation::create([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'allocation_date' => $request->allocation_date,
            'notes' => $request->notes,
            'status' => 'active'
        ]);
        
        return redirect()->route('allocations.index')
            ->with('success', 'Room allocated successfully!');
    }
    
    public function show(Allocation $allocation)
    {
        $allocation->load(['student', 'room']);
        return view('allocations.show', compact('allocation'));
    }
    
    public function edit(Allocation $allocation)
    {
        if ($allocation->status !== 'active') {
            return redirect()->route('allocations.index')
                ->with('error', 'Only active allocations can be edited!');
        }
        
        $students = Student::all();
        $rooms = Room::all();
        
        return view('allocations.edit', compact('allocation', 'students', 'rooms'));
    }
    
    public function update(Request $request, Allocation $allocation)
    {
        if ($allocation->status !== 'active') {
            return back()->with('error', 'Only active allocations can be updated!');
        }
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'allocation_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        $allocation->update($request->all());
        
        return redirect()->route('allocations.index')
            ->with('success', 'Allocation updated successfully!');
    }
    
    public function destroy(Allocation $allocation)
    {
        if ($allocation->status === 'active') {
            return back()->with('error', 'Cannot delete active allocation. Cancel it first!');
        }
        
        $allocation->delete();
        
        return redirect()->route('allocations.index')
            ->with('success', 'Allocation deleted successfully!');
    }
    
    public function cancel(Allocation $allocation)
    {
        if ($allocation->status !== 'active') {
            return back()->with('error', 'This allocation is not active!');
        }
        
        $allocation->update(['status' => 'cancelled']);
        
        return redirect()->route('allocations.index')
            ->with('success', 'Allocation cancelled successfully!');
    }
    
    public function complete(Allocation $allocation)
    {
        if ($allocation->status !== 'active') {
            return back()->with('error', 'This allocation is not active!');
        }
        
        $allocation->update([
            'status' => 'completed',
            'end_date' => now()
        ]);
        
        return redirect()->route('allocations.index')
            ->with('success', 'Allocation marked as completed!');
    }
    
    // Store new student from modal
    public function storeStudent(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'student_id' => 'required|unique:students,student_id',
                'phone' => 'nullable|string',
                'course' => 'nullable|string'
            ]);
            
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'phone' => $request->phone,
                'course' => $request->course,
                'year' => $request->year ?? '1st Year'
            ]);
            
            return response()->json([
                'success' => true,
                'student' => $student,
                'message' => 'Student created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    
    // Store new room from modal
    public function storeRoom(Request $request)
    {
        try {
            $request->validate([
                'room_number' => 'required|unique:rooms,room_number',
                'capacity' => 'required|integer|min:1',
                'floor' => 'nullable|string',
                'price_per_month' => 'nullable|numeric'
            ]);
            
            $room = Room::create([
                'room_number' => $request->room_number,
                'capacity' => $request->capacity,
                'floor' => $request->floor,
                'price_per_month' => $request->price_per_month,
                'status' => 'available'
            ]);
            
            return response()->json([
                'success' => true,
                'room' => $room,
                'message' => 'Room created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    
    // Get all students for API
    public function getStudents()
    {
        $students = Student::all();
        return response()->json($students);
    }
    
    // Get all rooms with availability
    public function getRooms()
    {
        $rooms = Room::all();
        $roomsWithAvailability = [];
        
        foreach($rooms as $room) {
            $occupiedCount = Allocation::where('room_id', $room->id)
                ->where('status', 'active')
                ->count();
            
            $room->available_beds = $room->capacity - $occupiedCount;
            $room->is_available = ($room->available_beds > 0);
            $roomsWithAvailability[] = $room;
        }
        
        return response()->json($roomsWithAvailability);
    }
}