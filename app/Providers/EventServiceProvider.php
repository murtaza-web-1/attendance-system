<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // ✅ Custom Events
        \App\Events\AttendanceMarked::class => [
            \App\Listeners\SendAttendanceWhatsAppNotification::class,
        ],
        \App\Events\LeaveRequested::class => [
            \App\Listeners\SendLeaveWhatsAppNotification::class,
        ],
        \App\Events\TaskAssigned::class => [
            \App\Listeners\SendTaskAssignWhatsAppNotification::class,
        ],
        \App\Events\TaskSubmissionUpdated::class => [
            \App\Listeners\SendTaskReviewWhatsAppNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
