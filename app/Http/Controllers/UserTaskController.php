<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use App\Models\Task;

class UserTaskController extends Controller
{
   public function myTasks()
{
    $tasks = Task::where('user_id', auth()->id())->latest()->get();
    return view('user.my-tasks', compact('tasks'));
}
}
