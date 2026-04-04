<?php

namespace App\Jobs;

use App\Models\Activity;
use App\Notifications\ActivityReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendActivityReminders implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $tomorrow = now()->addDay()->startOfDay();
        $tomorrowEnd = now()->addDay()->endOfDay();

        $activities = Activity::with(['user', 'contact'])
            ->where('is_done', false)
            ->whereBetween('due_date', [$tomorrow, $tomorrowEnd])
            ->whereNotNull('due_date')
            ->get();

        foreach ($activities as $activity) {
            $activity->user->notify(new ActivityReminderNotification($activity));
        }
    }
}
