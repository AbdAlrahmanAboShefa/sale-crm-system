<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DealStageChangedNotification extends Notification
{
    public function __construct(
        public $deal,
        public string $oldStage,
        public string $newStage
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'deal_stage_changed',
            'deal_id' => $this->deal->id,
            'deal_title' => $this->deal->title,
            'old_stage' => $this->oldStage,
            'new_stage' => $this->newStage,
            'message' => "Deal '{$this->deal->title}' moved from {$this->oldStage} to {$this->newStage}",
        ];
    }
}
