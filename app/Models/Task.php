<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    'user_id',
    'title',
    'description',
    'response',
    'status',
    'admin_feedback',
];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
