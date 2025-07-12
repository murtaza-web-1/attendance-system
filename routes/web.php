<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RoleController;

use App\Models\User;
//
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

// Logout normal user
Route::get('/logout', function () {
    Auth::guard('web')->logout();
    return redirect('/login');
})->middleware('auth:web')->name('logout');

//
// 👤 User Protected Routes
//
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Attendance
    Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');
    Route::post('/attendance/view', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/view-submit', [AttendanceController::class, 'getAttendanceData'])->name('attendance.view.submit');
    Route::post('/leave/mark', [AttendanceController::class, 'markLeave'])->name('leave.mark');
});

//
// 🔐 Admin Authentication Routes
//
Route::prefix('admin')->group(function () {
    // Login & Register
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

//
// 🛡️ Admin Protected Routes
//
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ✅ Attendance Management (with filtering & search)
    Route::get('/attendance', [AdminController::class, 'manageAttendance'])->name('admin.attendance.view');
    Route::get('/attendance/edit/{id}', [AdminController::class, 'edit'])->name('admin.attendance.edit');
    Route::post('/attendance/update/{id}', [AdminController::class, 'update'])->name('admin.attendance.update');
    Route::delete('/attendance/delete/{id}', [AdminController::class, 'destroy'])->name('admin.attendance.delete');
    Route::post('/attendance/toggle-leave/{id}', [AdminController::class, 'toggleLeave'])->name('admin.attendance.toggle-leave');

    // Leave Management
    Route::get('/leaves', [AdminController::class, 'leaves'])->name('admin.leaves');
    Route::post('/leaves/approve/{id}', [AdminController::class, 'approveLeave'])->name('admin.leaves.approve');

    // Attendance Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/reports', [AdminController::class, 'generateReport'])->name('admin.reports.generate');

    // Grading
    Route::get('/grading', [AdminController::class, 'grading'])->name('admin.grading');
    Route::post('/grading', [AdminController::class, 'saveGrading'])->name('admin.grading.save');
   
    // Task Management
    Route::get('/admin/create-task', [AdminController::class, 'createTaskForm'])->name('admin.createTask');
    Route::post('/admin/create-task', [AdminController::class, 'storeTask'])->name('admin.storeTask');
   
    // Task Feedback
    Route::get('/assign-role', [AdminController::class, 'assignRoleToUser'])->name('admin.assign.role');

     // Role Management
    Route::get('/manage-roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/assign-role/{user}', [RoleController::class, 'assign'])->name('admin.roles.assign');

});
