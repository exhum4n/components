<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class NotFoundException extends Exception
{
    #[Pure]
    public function __construct(string $message = "Not found.", int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
