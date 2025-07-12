<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $table = 'task_submissions'; 

    protected $fillable = [
        'user_id',
        'task_id',
        'response',
        'status',
        'admin_feedback',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
