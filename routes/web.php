<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome-new');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    });
    
    // Student Routes
    Route::middleware('student')->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    });
});
