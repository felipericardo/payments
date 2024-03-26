<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UnauthorizedTransactionException extends Exception
{
    public function __construct(string $message = "Unauthorized transaction!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
