<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| API Routes 
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/attendance/mark', [AttendanceController::class, 'mark']);
    Route::post('/attendance/leave', [AttendanceController::class, 'markLeave']);
    Route::post('/attendance/view', [AttendanceController::class, 'view']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    // List all tasks (admin or user)
    Route::get('/tasks', [TaskController::class, 'index']);

    // Show specific task
    Route::get('/tasks/{id}', [TaskController::class, 'show']);

    // Create new task (admin)
    Route::post('/tasks', [TaskController::class, 'store']);

    // Update user response / status (user)
    Route::put('/tasks/{id}', [TaskController::class, 'update']);

    // Admin feedback
    Route::put('/tasks/{id}/feedback', [TaskController::class, 'adminFeedback']);

    // Delete task (admin)
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

});
