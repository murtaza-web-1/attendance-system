<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AttendanceController;

// 🌐 Public Routes
//

// Redirect root to login
Route::get('/', fn() => redirect('/login'));

// User auth views
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::get('/login', fn() => view('auth.login'))->name('login');

// User auth submission
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->middleware('auth')->name('logout');

// User dashboard
Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');

//
// 🔐 Admin Authentication Routes
//

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});


//
// 🛡️ Admin Protected Routes
//
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Attendance management
    Route::get('/attendance/edit/{id}', [AdminController::class, 'edit'])->name('admin.attendance.edit');
    Route::post('/attendance/update/{id}', [AdminController::class, 'update'])->name('admin.attendance.update');
    Route::delete('/attendance/delete/{id}', [AdminController::class, 'destroy'])->name('admin.attendance.delete');
    Route::post('/attendance/toggle-leave/{id}', [AdminController::class, 'toggleLeave'])->name('admin.attendance.toggle-leave');

    // Leave management
    Route::get('/leaves', [AdminController::class, 'leaves'])->name('admin.leaves');
    Route::post('/leaves/approve/{id}', [AdminController::class, 'approveLeave'])->name('admin.leaves.approve');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/reports', [AdminController::class, 'generateReport'])->name('admin.reports.generate');

    // Grading
    Route::get('/grading', [AdminController::class, 'grading'])->name('admin.grading');
    Route::post('/grading', [AdminController::class, 'saveGrading'])->name('admin.grading.save');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/attendance/view', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view.submit');
    Route::post('/leave/mark', [AttendanceController::class, 'markLeave'])->name('leave.mark');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/attendance/view-data', [AttendanceController::class, 'getAttendanceData'])->name('attendance.view.submit');
});
