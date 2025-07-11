<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // ✅ Show all tasks (admin or user)
    public function index()
    {
        return response()->json(Task::with('user')->get());
    }

    // ✅ Store new task (admin assigns to user)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $task = Task::create($request->only('user_id', 'title', 'description'));

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    // ✅ Show single task
    public function show($id)
    {
        $task = Task::with('user')->findOrFail($id);
        return response()->json($task);
    }

    // ✅ Submit or update task response (user)
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'response' => 'nullable|string',
            'status' => 'in:pending,submitted',
        ]);

        $task->update($request->only('response', 'status'));

        return response()->json(['message' => 'Task updated', 'task' => $task]);
    }

    // ✅ Admin feedback
    public function adminFeedback(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'admin_feedback' => 'nullable|string',
        ]);

        $task->admin_feedback = $request->admin_feedback;
        $task->save();

        return response()->json(['message' => 'Feedback saved', 'task' => $task]);
    }

    // ✅ Delete task (admin only)
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}