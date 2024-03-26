<?php

namespace App\Jobs;

use App\Entities\User;
use App\Jobs\Job;
use App\Services\NotificationService;

class SendNotification extends Job
{
    private User $target;
    private string $content;

    public function __construct(User $target, string $content)
    {
        $this->target  = $target;
        $this->content = $content;
    }

    public function handle(NotificationService $notificationService)
    {
        $notificationService->send($this->target, $this->content);
    }
}
