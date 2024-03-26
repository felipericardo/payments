<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidPayerException extends Exception
{
    public function __construct(string $message = "Invalid payer!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
