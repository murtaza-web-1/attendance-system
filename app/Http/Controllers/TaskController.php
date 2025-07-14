<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function submit(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $task = Task::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $task->response = $request->response;
        $task->status = 'submitted';
        $task->save();

        return redirect()->back()->with('success', 'Task submitted successfully!');
    }
}

