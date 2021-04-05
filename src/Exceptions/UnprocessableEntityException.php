<?php

namespace Exhum4n\Components\Exceptions;

use Exception;
use Throwable;

abstract class UnprocessableEntityException extends Exception
{
    /**
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }
}
