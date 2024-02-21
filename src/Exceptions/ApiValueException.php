<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class ApiValueException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        if ($message === '') {
            $message =  'API Value not valid';
        }
        if ($code === 0) {
            $code = 400;
        }

        parent::__construct($message, $code, $previous);
    }

    public static function createNotValid(string $paramName): self
    {
        return new self('API Value not valid: '. $paramName);
    }
}
