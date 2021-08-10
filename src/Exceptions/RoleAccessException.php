<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Throwable;
use Exception;

class RoleAccessException extends Exception
{
    /**
     * AccessException constructor.
     *
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct($message = 'Your role has no access to this action', Throwable $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
