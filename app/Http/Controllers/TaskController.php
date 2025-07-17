<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskSubmission;

class TaskController extends Controller
{
    
    public function submit(Request $request, $id)
{
    $task = Task::findOrFail($id);
    $task->response = $request->input('response');
    $task->status = 'submitted';
    $task->save();

    return back()->with('success', 'Task submitted successfully!');
}
public function myTasks()
{
    $tasks = Task::where('user_id', auth()->id())->get();

    return response()->json($tasks);
}

//     public function submit(Request $request, $id)
// {
//     $request->validate([
//         'response' => 'required|string',
//     ]);

//     // Prevent duplicate submission
//     $already = TaskSubmission::where('user_id', auth()->id())
//                              ->where('task_id', $id)
//                              ->exists();

//     if ($already) {
//         return redirect()->back()->with('error', 'You already submitted this task.');
//     }

//     // Save to task_submissions table
//     TaskSubmission::create([
//         'user_id' => auth()->id(),
//         'task_id' => $id,
//         'response' => $request->response,
//         'status' => 'pending',
//     ]);

//     return redirect()->back()->with('success', 'Task submitted successfully!');
// }

}

