<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;

class RoomAllocationController extends Controller
{
    /**
     * Show allocation management page.
     */
    public function index()
    {
        $allocations = Student::with('user', 'room')
            ->whereHas('room')
            ->paginate(15);
        
        $unallocatedCount = Student::doesntHave('room')->count();
        $totalStudents = Student::count();

        return view('admin.allocations.index', [
            'allocations' => $allocations,
            'unallocatedCount' => $unallocatedCount,
            'totalStudents' => $totalStudents,
        ]);
    }

    /**
     * Show create allocation form.
     */
    public function create()
    {
        $students = Student::doesntHave('room')->with('user')->get();
        $rooms = Room::available()->get();

        return view('admin.allocations.create', [
            'students' => $students,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Store a new allocation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id|unique:room_allocations,student_id',
            'room_id' => 'required|exists:rooms,id',
            'allocation_date' => 'required|date',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $room = Room::findOrFail($validated['room_id']);

        // Check if room has available beds
        if ($room->available_beds <= 0) {
            return back()->withErrors(['room_id' => 'This room has no available beds']);
        }

        // Allocate student to room
        $student->room()->attach($room->id, [
            'allocation_date' => $validated['allocation_date'],
        ]);

        // Reduce available beds
        $room->decrement('available_beds');

        // Update room status if all beds are filled
        if ($room->available_beds === 0) {
            $room->update(['status' => 'occupied']);
        }

        return redirect()->route('allocations.index')->with('success', 'Student allocated to room successfully');
    }

    /**
     * Show unallocated students.
     */
    public function unallocated()
    {
        $students = Student::doesntHave('room')->with('user')->paginate(15);
        
        return view('admin.allocations.unallocated', [
            'students' => $students,
        ]);
    }

    /**
     * Show form to allocate a specific student.
     */
    public function allocateStudent(Student $student)
    {
        if ($student->room()->exists()) {
            return back()->withErrors(['student' => 'This student is already allocated to a room']);
        }

        $rooms = Room::available()->get();

        return view('admin.allocations.allocate-student', [
            'student' => $student,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Store allocation for a specific student.
     */
    public function storeStudentAllocation(Request $request, Student $student)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'allocation_date' => 'required|date',
        ]);

        if ($student->room()->exists()) {
            return back()->withErrors(['student' => 'This student is already allocated']);
        }

        $room = Room::findOrFail($validated['room_id']);

        if ($room->available_beds <= 0) {
            return back()->withErrors(['room_id' => 'This room has no available beds']);
        }

        // Allocate student to room
        $student->room()->attach($room->id, [
            'allocation_date' => $validated['allocation_date'],
        ]);

        // Reduce available beds
        $room->decrement('available_beds');

        // Update room status if all beds are filled
        if ($room->available_beds === 0) {
            $room->update(['status' => 'occupied']);
        }

        return redirect()->route('allocations.index')->with('success', "{$student->user->name} allocated to Room {$room->room_number}");
    }

    /**
     * Release a student from room allocation.
     */
    public function release(Student $student)
    {
        $allocation = $student->room()->first();

        if (!$allocation) {
            return back()->withErrors(['student' => 'Student is not allocated to any room']);
        }

        $room = $allocation;
        $roomId = $allocation->id;

        // Detach student from room
        $student->room()->detach($roomId);

        // Increase available beds
        $room->increment('available_beds');

        // Update room status back to available if beds are now available
        if ($room->available_beds > 0 && $room->status === 'occupied') {
            $room->update(['status' => 'available']);
        }

        return redirect()->route('allocations.index')->with('success', 'Student released from room successfully');
    }
}
