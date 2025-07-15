<?php

namespace App\Listeners;

use App\Events\TaskSubmissionUpdated;
use App\Services\WhatsAppService;

class SendTaskReviewWhatsAppNotification
{
    public function handle(TaskSubmissionUpdated $event)
    {
        $submission = $event->submission;
        $user = $submission->user;
        $status = ucfirst($submission->status);

        $message = "📋 *Task {$status}*\n"
                 . "📌 Task: {$submission->task->title}\n";

        if ($submission->admin_feedback) {
            $message .= "📝 Feedback: {$submission->admin_feedback}";
        }

        (new WhatsAppService())->sendMessage($user->phone, $message);
    }
}
