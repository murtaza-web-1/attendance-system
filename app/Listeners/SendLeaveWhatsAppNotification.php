<?php

namespace App\Listeners;

use App\Events\LeaveRequested;
use App\Services\WhatsAppService;

class SendLeaveWhatsAppNotification
{
    public function handle(LeaveRequested $event)
    {
        $user = $event->user;
        (new WhatsAppService())->sendMessage(
            $user->phone,
            "📤 Leave Request Submitted\n👤 Name: {$user->name}\n📅 Date: {$event->date}\n📝 Reason: {$event->reason}"
        );
    }
}
