<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show all payments (Admin).
     */
    public function index()
    {
        $payments = Payment::with('student.user')->paginate(15);
        
        $stats = [
            'total_payments' => Payment::sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            'completed_payments' => Payment::where('status', 'paid')->sum('amount'),
            'total_records' => Payment::count(),
        ];

        return view('admin.payments.index', ['payments' => $payments, 'stats' => $stats]);
    }

    /**
     * Show create payment form.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        return view('admin.payments.create', ['students' => $students]);
    }

    /**
     * Store a new payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,paid',
            'payment_method' => 'required|string|in:cash,check,transfer,online',
            'transaction_id' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment created successfully');
    }

    /**
     * Show edit payment form.
     */
    public function edit(Payment $payment)
    {
        $students = Student::with('user')->get();
        return view('admin.payments.edit', ['payment' => $payment, 'students' => $students]);
    }

    /**
     * Update a payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,paid',
            'payment_method' => 'required|string|in:cash,check,transfer,online',
            'transaction_id' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully');
    }

    /**
     * Delete a payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully');
    }

    /**
     * Mark payment as paid.
     */
    public function markPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment marked as paid');
    }

    /**
     * Generate payment receipt.
     */
    public function receipt(Payment $payment)
    {
        return view('admin.payments.receipt', ['payment' => $payment->load('student.user')]);
    }

    /**
     * Get student payments (for student dashboard).
     */
    public function studentPayments()
    {
        $student = auth()->user()->student;
        $payments = $student->payments()->latest('due_date')->paginate(10);
        $totalDue = $student->payments()->where('status', 'pending')->sum('amount');

        return view('student.payments', [
            'payments' => $payments,
            'totalDue' => $totalDue,
        ]);
    }
}
