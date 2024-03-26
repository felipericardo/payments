<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class PayerNotFoundException extends Exception
{
    public function __construct(string $message = "Payer not found!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
