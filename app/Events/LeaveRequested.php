<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRequested
{
    use Dispatchable, SerializesModels;

    public $user;
    public $reason;
    public $date;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $reason, $date)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->date = $date;
    }
}
