<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'response',
        'status',
        'admin_feedback',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
