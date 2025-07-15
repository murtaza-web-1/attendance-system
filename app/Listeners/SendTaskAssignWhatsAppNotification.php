<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Services\WhatsAppService;

class SendTaskAssignWhatsAppNotification
{
    public function handle(TaskAssigned $event)
    {
        $user = $event->user;
        $task = $event->task;

        (new WhatsAppService())->sendMessage(
            $user->phone,
            "📌 *New Task Assigned*\n📋 {$task->title}\nPlease check your dashboard."
        );
    }
}
