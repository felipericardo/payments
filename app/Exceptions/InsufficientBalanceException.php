<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InsufficientBalanceException extends Exception
{
    public function __construct(string $message = "Insufficient balance!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
