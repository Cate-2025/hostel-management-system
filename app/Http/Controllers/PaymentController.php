<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Show all payments (Admin).
     */
    public function index()
    {
        $payments = Payment::with(['student', 'allocation'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingAmount = Payment::where('status', 'pending')->sum('amount');
        $totalPayments = Payment::count();
        
        $stats = [
            'total_payments' => Payment::sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            'completed_payments' => Payment::where('status', 'completed')->sum('amount'),
            'total_records' => Payment::count(),
        ];

        return view('payments.index', compact('payments', 'totalRevenue', 'pendingAmount', 'totalPayments', 'stats'));
    }
    
    /**
     * Show create payment form.
     */
    public function create()
    {
        $students = Student::has('activeAllocation')->with('activeAllocation')->get();
        return view('payments.create', compact('students'));
    }
    
    /**
     * Store a new payment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:UGX,USD',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,card',
            'transaction_id' => 'nullable|string',
            'month' => 'nullable|string',
            'year' => 'nullable|string',
            'description' => 'nullable|string'
        ]);
        
        $allocation = Allocation::where('student_id', $request->student_id)
            ->where('status', 'active')
            ->first();
            
        if (!$allocation) {
            return back()->with('error', 'Student has no active room allocation!');
        }
        
        // Convert amount to UGX if needed (exchange rate: 1 USD = 3800 UGX)
        $amountInUGX = $request->amount;
        
        if ($request->currency == 'USD') {
            $amountInUGX = $request->amount * 3800; // Convert USD to UGX
        }
        
        $receiptNumber = Payment::generateReceiptNumber();
        
        Payment::create([
            'student_id' => $request->student_id,
            'allocation_id' => $allocation->id,
            'amount' => $amountInUGX,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'receipt_number' => $receiptNumber,
            'description' => $request->description,
            'month' => $request->month ?? date('F'),
            'year' => $request->year ?? date('Y'),
            'status' => 'completed'
        ]);
        
        $currencySymbol = $request->currency == 'USD' ? '$' : 'UGX';
        $displayAmount = $request->currency == 'USD' ? '$' . number_format($request->amount, 2) : 'UGX ' . number_format($request->amount, 2);
        
        return redirect()->route('payments.index')
            ->with('success', "Payment recorded successfully! Receipt: $receiptNumber | Amount: $displayAmount");
    }
    
    /**
     * Show payment details.
     */
    public function show(Payment $payment)
    {
        $usdAmount = $payment->amount / 3800;
        return view('payments.show', compact('payment', 'usdAmount'));
    }
    
    /**
     * Show edit payment form.
     */
    public function edit(Payment $payment)
    {
        $students = Student::all();
        return view('payments.edit', compact('payment', 'students'));
    }
    
    /**
     * Update a payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,card',
            'status' => 'required|in:pending,completed,failed'
        ]);
        
        $payment->update($request->all());
        
        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully!');
    }
    
    /**
     * Delete a payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully!');
    }
    
    /**
     * Generate payment receipt.
     */
    public function receipt(Payment $payment)
    {
        $usdAmount = $payment->amount / 3800;
        return view('payments.receipt', compact('payment', 'usdAmount'));
    }
    
    /**
     * Print payment receipt.
     */
    public function printReceipt(Payment $payment)
    {
        $usdAmount = $payment->amount / 3800;
        return view('payments.print', compact('payment', 'usdAmount'));
    }
    
    /**
     * Mark payment as paid.
     */
    public function markPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'payment_date' => now(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment marked as paid');
    }
    
    /**
     * Get student balance (AJAX).
     */
    public function getStudentBalance($studentId)
    {
        $student = Student::findOrFail($studentId);
        $allocation = Allocation::where('student_id', $studentId)
            ->where('status', 'active')
            ->first();
            
        if (!$allocation) {
            return response()->json([
                'has_allocation' => false,
                'message' => 'No active allocation'
            ]);
        }
        
        $roomPriceUGX = $allocation->room->price_per_month ?? 0;
        $roomPriceUSD = $roomPriceUGX / 3800;
        
        $paymentsUGX = Payment::where('student_id', $studentId)
            ->where('status', 'completed')
            ->sum('amount');
        $paymentsUSD = $paymentsUGX / 3800;
        
        $balanceUGX = $roomPriceUGX - $paymentsUGX;
        $balanceUSD = $balanceUGX / 3800;
        
        return response()->json([
            'has_allocation' => true,
            'room_price_ugx' => $roomPriceUGX,
            'room_price_usd' => round($roomPriceUSD, 2),
            'total_paid_ugx' => $paymentsUGX,
            'total_paid_usd' => round($paymentsUSD, 2),
            'balance_ugx' => max(0, $balanceUGX),
            'balance_usd' => round(max(0, $balanceUSD), 2),
            'room_number' => $allocation->room->room_number
        ]);
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