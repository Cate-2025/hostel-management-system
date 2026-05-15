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

    /**
     * Show admin reports.
     */
    public function reports()
    {
        // Room occupancy report
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

        // Payment reports
        $totalPayments = Payment::sum('amount');
        $paidPayments = Payment::where('status', 'paid')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');
        $overduePayments = Payment::where('status', 'overdue')->sum('amount');

        // Monthly payment summary (current year)
        $monthlyPayments = Payment::selectRaw("strftime('%m', payment_date) as month, SUM(amount) as total")
            ->where('status', 'paid')
            ->whereYear('payment_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Student payment summary
        $studentPayments = Student::with('user')
            ->withSum('payments', 'amount')
            ->withSum(['payments as paid_payments' => function ($query) {
                $query->where('status', 'paid');
            }], 'amount')
            ->withSum(['payments as pending_payments' => function ($query) {
                $query->where('status', 'pending');
            }], 'amount')
            ->get();

        return view('admin.reports', [
            'totalRooms' => $totalRooms,
            'occupiedRooms' => $occupiedRooms,
            'availableRooms' => $availableRooms,
            'occupancyRate' => $occupancyRate,
            'totalPayments' => $totalPayments,
            'paidPayments' => $paidPayments,
            'pendingPayments' => $pendingPayments,
            'overduePayments' => $overduePayments,
            'monthlyPayments' => $monthlyPayments,
            'studentPayments' => $studentPayments,
        ]);
    }
}
