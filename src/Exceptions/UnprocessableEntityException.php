<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use Throwable;

abstract class UnprocessableEntityException extends Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }
}
