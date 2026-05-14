<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomAllocationController;
use App\Http\Controllers\PaymentController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome-new');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Student Management
        Route::resource('students', StudentController::class);
        
        // Room Management
        Route::resource('rooms', RoomController::class);
        
        // Room Allocation
        Route::get('/allocations', [RoomAllocationController::class, 'index'])->name('allocations.index');
        Route::get('/allocations/create', [RoomAllocationController::class, 'create'])->name('allocations.create');
        Route::post('/allocations', [RoomAllocationController::class, 'store'])->name('allocations.store');
        Route::get('/allocations/unallocated', [RoomAllocationController::class, 'unallocated'])->name('allocations.unallocated');
        Route::get('/allocations/student/{student}', [RoomAllocationController::class, 'allocateStudent'])->name('allocations.allocate-student');
        Route::post('/allocations/student/{student}', [RoomAllocationController::class, 'storeStudentAllocation'])->name('allocations.store-student');
        Route::delete('/allocations/student/{student}/release', [RoomAllocationController::class, 'release'])->name('allocations.release');
        
        // Payment Management
        Route::resource('payments', PaymentController::class);
        Route::patch('/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.mark-paid');
        Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
        
        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    });
    
    // Student Routes
    Route::middleware('student')->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/payments', [PaymentController::class, 'studentPayments'])->name('student.payments');
    });
});
