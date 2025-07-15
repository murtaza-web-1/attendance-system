<?php

namespace App\Listeners;

use App\Events\AttendanceMarked;
use App\Services\WhatsAppService;

class SendAttendanceWhatsAppNotification
{
    public function handle(AttendanceMarked $event)
    {
        $user = $event->user;
        (new WhatsAppService())->sendMessage(
            $user->phone,
            "✅ Hi {$user->name}, your attendance has been marked."
        );
    }
}
