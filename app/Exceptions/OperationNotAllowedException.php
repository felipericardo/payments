<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class OperationNotAllowedException extends Exception
{
    public function __construct(string $message = "Operation not allowed!", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
