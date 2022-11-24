<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

abstract class UnprocessableEntity extends Exception
{
    #[Pure]
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }
}
