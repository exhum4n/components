<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use JetBrains\PhpStorm\Pure;
use Throwable;

class Validation extends UnprocessableEntity
{
    #[Pure]
    public function __construct($message, ?Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
