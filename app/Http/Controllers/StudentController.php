<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Show all students (Admin).
     */
    public function index()
    {
        $students = Student::with('user')->paginate(15);
        return view('admin.students.index', ['students' => $students]);
    }

    /**
     * Show create student form.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a new student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'enrollment_number' => 'required|string|unique:students',
            'contact_number' => 'required|string',
            'parent_name' => 'nullable|string',
            'parent_contact' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password123'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create student
        Student::create([
            'user_id' => $user->id,
            'enrollment_number' => $validated['enrollment_number'],
            'contact_number' => $validated['contact_number'],
            'parent_name' => $validated['parent_name'],
            'parent_contact' => $validated['parent_contact'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }

    /**
     * Show edit student form.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', ['student' => $student]);
    }

    /**
     * Update a student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'enrollment_number' => 'required|string|unique:students,enrollment_number,'.$student->id,
            'contact_number' => 'required|string',
            'parent_name' => 'nullable|string',
            'parent_contact' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student
        $student->update([
            'enrollment_number' => $validated['enrollment_number'],
            'contact_number' => $validated['contact_number'],
            'parent_name' => $validated['parent_name'],
            'parent_contact' => $validated['parent_contact'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully');
    }

    /**
     * Delete a student.
     */
    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully');
    }

    /**
     * Show the student dashboard.
     */
    public function dashboard()
    {
        $student = auth()->user()->student;
        $room = $student->room()->latest('room_allocations.allocation_date')->first();
        $payments = $student->payments()->latest('due_date')->paginate(10);

        return view('student.dashboard', [
            'student' => $student,
            'room' => $room,
            'payments' => $payments,
        ]);
    }

    /**
     * Show student profile.
     */
    public function profile()
    {
        $student = auth()->user()->student;
        return view('student.profile', ['student' => $student]);
    }

    /**
     * Update student profile.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'contact_number' => 'required|string',
            'parent_contact' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        auth()->user()->student->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Show room allocation details.
     */
    public function roomDetails()
    {
        $student = auth()->user()->student;
        $room = $student->room()->latest('room_allocations.allocation_date')->first();

        if (!$room) {
            return redirect()->back()->with('error', 'No room allocated');
        }

        return view('student.room', ['room' => $room]);
    }

    /**
     * Show payment history.
     */
    public function payments()
    {
        $student = auth()->user()->student;
        $payments = $student->payments()->latest('due_date')->paginate(15);
        $totalDue = $student->payments()->where('status', 'pending')->sum('amount');

        return view('student.payments', [
            'payments' => $payments,
            'totalDue' => $totalDue,
        ]);
    }
}
