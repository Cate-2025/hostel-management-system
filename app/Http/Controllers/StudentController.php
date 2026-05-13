<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
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
