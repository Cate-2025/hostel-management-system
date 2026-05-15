<?php

namespace App\Http\Controllers;

use App\Models\Student;
<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> ed5b922bbb7a8f3fd2397b2769a66e738783be43
use Illuminate\Http\Request;

class StudentController extends Controller
{
<<<<<<< HEAD
    public function index()
=======
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
>>>>>>> ed5b922bbb7a8f3fd2397b2769a66e738783be43
    {
        $students = Student::latest()->paginate(15);
        return view('students.index', compact('students'));
    }
    
    public function create()
    {
        return view('students.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'student_id' => 'required|unique:students',
            'phone' => 'nullable|string',
            'course' => 'nullable|string',
            'year' => 'nullable|string'
        ]);
        
        Student::create($request->all());
        
        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }
    
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }
    
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'student_id' => 'required|unique:students,student_id,' . $student->id,
            'phone' => 'nullable|string',
            'course' => 'nullable|string',
            'year' => 'nullable|string'
        ]);
        
        $student->update($request->all());
        
        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }
    
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }
}