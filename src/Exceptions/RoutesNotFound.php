<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use Throwable;

class RoutesNotFound extends Exception
{
    public function __construct(string $message = "routes_file_not_found", int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
