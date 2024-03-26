<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotificationNotSentException extends Exception
{
    public function __construct(string $message = "Notification not sent!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
