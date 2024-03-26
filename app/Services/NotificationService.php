<?php

namespace App\Services;

use App\Entities\User;
use App\Exceptions\NotificationNotSentException;
use App\Jobs\SendNotification;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function sendToQueue(User $target, string $content)
    {
        dispatch(new SendNotification($target, $content));
    }

    public function send(User $target, string $content)
    {
        dump(sprintf('Target: %s', $target->getName()));
        dump(sprintf('Content: %s', $content));

//        if (Http::get(env('NOTIFICATION_API'))['message'] !== 'Ok') {
//            throw new NotificationNotSentException();
//        }
    }
}
