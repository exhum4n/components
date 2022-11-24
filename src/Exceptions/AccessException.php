<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use JetBrains\PhpStorm\Pure;
use Throwable;
use Exception;

class AccessException extends Exception
{
    #[Pure]
    public function __construct($message = 'You has no access to this action.', Throwable $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
