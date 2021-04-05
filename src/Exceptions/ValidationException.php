<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Throwable;

class ValidationException extends UnprocessableEntityException
{
    /**
     * @param $message
     * @param Throwable|null $previous
     */
    public function __construct($message, ?Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
