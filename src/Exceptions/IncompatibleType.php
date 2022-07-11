<?php

namespace Exhum4n\Components\Exceptions;

use Exception;

class IncompatibleType extends Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, 501);
    }
}
