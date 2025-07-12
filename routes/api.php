<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskSubmissionController; // ✅ updated controller

// ==============================
// 🔐 Authentication APIs
// ==============================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ==============================
// 👤 Student APIs
// ==============================
Route::middleware('auth:sanctum')->group(function () {
    // Task Management
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/submit-task', [TaskSubmissionController::class, 'submitTask']);
    Route::get('/my-submissions', [TaskSubmissionController::class, 'mySubmissions']);

    // Attendance
    Route::post('/attendance/present', [AttendanceController::class, 'markPresent']);
    Route::post('/attendance/leave', [AttendanceController::class, 'markLeave']);
    Route::get('/attendance', [AttendanceController::class, 'viewAttendance']);
});

// ==============================
// 🛡️ Admin APIs
// ==============================
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Task Management
    Route::post('/tasks', [AdminController::class, 'createTask']);
    Route::post('/tasks/{id}/feedback', [AdminController::class, 'addFeedback']);

    // Student Management
    Route::get('/students', [AdminController::class, 'listStudents']);

    // Leave Approval
    Route::post('/leaves/{id}/approve', [AdminController::class, 'approveLeave']);

    // Attendance Reports
    Route::get('/attendance/report', [AdminController::class, 'attendanceReport']);

    // Grading
    Route::post('/grade/{student_id}', [AdminController::class, 'assignGrade']);

    // Task Submission Review
    Route::get('/all-submissions', [TaskSubmissionController::class, 'allSubmissions']);
    Route::post('/submission-status/{id}', [TaskSubmissionController::class, 'updateStatus']);
});
