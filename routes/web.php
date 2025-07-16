<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\TaskController;
use App\Models\Task;


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

    // ✅ Dashboard - with assigned tasks
    Route::get('/dashboard', function () {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('tasks'));
    })->name('dashboard');

    // ✅ Attendance Routes
    Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');
    Route::post('/attendance/view', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/view-submit', [AttendanceController::class, 'getAttendanceData'])->name('attendance.view.submit');
    Route::post('/leave/mark', [AttendanceController::class, 'markLeave'])->name('leave.mark');
    Route::get('/user/attendance', [AttendanceController::class, 'userAttendance'])->name('user.attendance');
    Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');

    // ✅ Task Submission by user
    Route::post('/tasks/{id}/submit', [TaskController::class, 'submit'])->name('task.submit');

    // ✅ Admin Task Submissions View + Action
    Route::get('/submitted-tasks', [AdminController::class, 'submittedTasks'])->name('admin.submissions');
    Route::post('/submitted-tasks/{id}/update', [AdminController::class, 'updateSubmissionStatus'])->name('admin.submissions.update');
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


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // ✅ Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ✅ Attendance Management
    Route::get('/attendance', [AdminController::class, 'manageAttendance'])->name('admin.attendance.view');
    Route::get('/attendance/edit/{id}', [AdminController::class, 'edit'])->name('admin.attendance.edit');
    Route::post('/attendance/update/{id}', [AdminController::class, 'update'])->name('admin.attendance.update');
    Route::delete('/attendance/delete/{id}', [AdminController::class, 'destroy'])->name('admin.attendance.delete');
    Route::post('/attendance/toggle-leave/{id}', [AdminController::class, 'toggleLeave'])->name('admin.attendance.toggle-leave');

    // ✅ Leave Requests
    Route::get('/leaves', [AdminController::class, 'leaves'])->name('admin.leaves');
    Route::post('/leaves/approve/{id}', [AdminController::class, 'approveLeave'])->name('admin.leaves.approve');

    // ✅ Attendance Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/reports', [AdminController::class, 'generateReport'])->name('admin.reports.generate');

    // ✅ Grading
    Route::get('/grading', [AdminController::class, 'grading'])->name('admin.grading');
    Route::post('/grading', [AdminController::class, 'saveGrading'])->name('admin.grading.save');

    // ✅ Task Management
    Route::get('/create-task', [AdminController::class, 'createTaskForm'])->name('admin.createTask');
    Route::post('/create-task', [AdminController::class, 'storeTask'])->name('admin.storeTask');

    // ✅ Role Management
    Route::get('/manage-roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/assign-role/{user}', [RoleController::class, 'assign'])->name('admin.roles.assign');
    Route::post('/admin/roles/create', [RoleController::class, 'storeAjax'])->name('admin.roles.store.ajax');
    Route::delete('/admin/roles/delete', [RoleController::class, 'destroy'])->name('admin.roles.delete');



    // ✅ Permission Management
    Route::get('/manage-permissions', [RoleController::class, 'permissions'])->name('admin.permissions.index');
    Route::post('/assign-permission', [RoleController::class, 'assignPermission'])->name('admin.permissions.assign');
    Route::post('/admin/permissions/remove', [RoleController::class, 'removePermission'])->name('admin.permissions.remove');


    // ✅ User List
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');

});

//only users with mark-attendance permission can access this route.
Route::middleware(['auth', 'permission:mark attendance'])->group(function () {
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance']);
});
