<?php

use App\Http\Controllers\AllocationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==================== AUTHENTICATION ROUTES ====================

// Login Routes
Route::get('/login', function() { 
    return view('auth.login'); 
})->name('login');

Route::post('/login', function(\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    
    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login');

// Register Routes
Route::get('/register', function() { 
    return view('auth.register'); 
})->name('register');

Route::post('/register', function(\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);
    
    Auth::login($user);
    return redirect('/dashboard');
})->name('register');

// Logout Route
Route::post('/logout', function(\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ==================== PROTECTED ROUTES (Requires Authentication) ====================

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function() { 
        $totalRooms = \App\Models\Room::count();
        $totalStudents = \App\Models\Student::count();
        $activeAllocations = \App\Models\Allocation::where('status', 'active')->count();
        $recentAllocations = \App\Models\Allocation::with(['student', 'room'])->latest()->take(5)->get();
        
        return view('dashboard', compact('totalRooms', 'totalStudents', 'activeAllocations', 'recentAllocations')); 
    })->name('dashboard');
    
    // Profile & Settings (Placeholders)
    Route::get('/profile', function() { 
        return view('profile.index'); 
    })->name('profile');
    
    Route::get('/settings', function() { 
        return view('settings.index'); 
    })->name('settings');
    
    // ==================== STUDENT MANAGEMENT ====================
    Route::resource('students', StudentController::class);
    
    // ==================== ROOM MANAGEMENT ====================
    Route::resource('rooms', RoomController::class);
    
    // ==================== ALLOCATION MANAGEMENT ====================
    Route::resource('allocations', AllocationController::class);
    Route::patch('allocations/{allocation}/cancel', [AllocationController::class, 'cancel'])->name('allocations.cancel');
    Route::patch('allocations/{allocation}/complete', [AllocationController::class, 'complete'])->name('allocations.complete');
    
    // API Routes for Dynamic Adding (AJAX)
    Route::post('students/store', [AllocationController::class, 'storeStudent'])->name('students.store');
    Route::post('rooms/store', [AllocationController::class, 'storeRoom'])->name('rooms.store');
    Route::get('students/list', [AllocationController::class, 'getStudents'])->name('students.list');
    Route::get('rooms/list', [AllocationController::class, 'getRooms'])->name('rooms.list');
    
    // ==================== PAYMENT MANAGEMENT ====================
    Route::resource('payments', PaymentController::class);
    Route::get('payments/receipt/{payment}', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::get('payments/print/{payment}', [PaymentController::class, 'printReceipt'])->name('payments.print');
    Route::get('payments/student/{id}/balance', [PaymentController::class, 'getStudentBalance'])->name('payments.balance');
    
    // ==================== REPORTS MANAGEMENT ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/payments', [ReportController::class, 'paymentReport'])->name('payments');
        Route::get('/allocations', [ReportController::class, 'allocationReport'])->name('allocations');
        Route::get('/occupancy', [ReportController::class, 'occupancyReport'])->name('occupancy');
        Route::get('/financial', [ReportController::class, 'financialReport'])->name('financial');
    });
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return redirect('/');
});