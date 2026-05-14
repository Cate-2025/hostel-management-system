<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
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