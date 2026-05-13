<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');
        $totalPayments = Payment::sum('amount');

        return view('admin.dashboard', [
            'totalStudents' => $totalStudents,
            'totalRooms' => $totalRooms,
            'occupiedRooms' => $occupiedRooms,
            'availableRooms' => $availableRooms,
            'pendingPayments' => $pendingPayments,
            'totalPayments' => $totalPayments,
        ]);
    }

    /**
     * Show all students.
     */
    public function students()
    {
        $students = Student::with('user')->paginate(15);
        return view('admin.students.index', ['students' => $students]);
    }

    /**
     * Show student details.
     */
    public function showStudent(Student $student)
    {
        return view('admin.students.show', ['student' => $student]);
    }

    /**
     * Show all rooms.
     */
    public function rooms()
    {
        $rooms = Room::paginate(15);
        return view('admin.rooms.index', ['rooms' => $rooms]);
    }

    /**
     * Show room details.
     */
    public function showRoom(Room $room)
    {
        $students = $room->students()->paginate(10);
        return view('admin.rooms.show', ['room' => $room, 'students' => $students]);
    }

    /**
     * Show all payments.
     */
    public function payments()
    {
        $payments = Payment::with('student.user')->paginate(15);
        return view('admin.payments.index', ['payments' => $payments]);
    }
}
