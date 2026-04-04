<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $activity
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $contactName = $this->activity->contact->name ?? 'Unknown';

        return (new MailMessage)
            ->subject("Reminder: {$this->activity->type} due tomorrow")
            ->line("This is a reminder that you have a {$this->activity->type} scheduled for tomorrow.")
            ->line("Contact: {$contactName}")
            ->line('Note: '.substr($this->activity->note, 0, 100))
            ->action('View Activity', url('/'))
            ->line('Please complete or reschedule this activity.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'activity_reminder',
            'activity_id' => $this->activity->id,
            'message' => "Reminder: {$this->activity->type} due tomorrow",
            'contact_name' => $this->activity->contact->name ?? 'Unknown',
        ];
    }
}
