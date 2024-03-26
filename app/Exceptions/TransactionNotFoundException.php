<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class TransactionNotFoundException extends Exception
{
    public function __construct(string $message = "Transaction not found!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
