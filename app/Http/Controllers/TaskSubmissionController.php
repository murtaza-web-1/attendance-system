<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    // ✅ Submit a task response by student
    public function submitTask(Request $request)
    {
        $request->validate([
            'task_id'  => 'required|exists:tasks,id',
            'response' => 'required|string',
        ]);

        $user = auth('api')->user();

        // Check if user already submitted this task
        $existing = TaskSubmission::where('user_id', $user->id)
                                  ->where('task_id', $request->task_id)
                                  ->first();

        if ($existing) {
            return response()->json(['message' => 'Task already submitted'], 409);
        }

        $submission = TaskSubmission::create([
            'user_id' => $user->id,
            'task_id' => $request->task_id,
            'response' => $request->response,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Task submitted successfully',
            'data' => $submission
        ], 201);
    }

    // ✅ View all submissions of logged-in student
    public function mySubmissions()
    {
        $user = auth('api')->user();

        $submissions = TaskSubmission::with('task')
                        ->where('user_id', $user->id)
                        ->get();

        return response()->json($submissions);
    }

    // ✅ View all submissions (Admin only)
    public function allSubmissions()
    {
        $submissions = TaskSubmission::with('user', 'task')->get();

        return response()->json($submissions);
    }

    // ✅ Approve or reject submission (Admin only)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|in:approved,rejected',
            'admin_feedback'  => 'nullable|string'
        ]);

        $submission = TaskSubmission::findOrFail($id);
        $submission->status = $request->status;
        $submission->admin_feedback = $request->admin_feedback ?? '';
        $submission->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data'    => $submission
        ]);
    }
}
