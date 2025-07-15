<?php

namespace App\Events;

use App\Models\TaskSubmission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskSubmissionUpdated
{
    use Dispatchable, SerializesModels;

    public $submission;

    /**
     * Create a new event instance.
     */
    public function __construct(TaskSubmission $submission)
    {
        $this->submission = $submission;
    }
}
