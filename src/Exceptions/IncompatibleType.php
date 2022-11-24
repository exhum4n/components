<?php

namespace Exhum4n\Components\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class IncompatibleType extends Exception
{
    #[Pure]
    public function __construct(string $message = "")
    {
        parent::__construct($message, 501);
    }
}
