<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;

class InstallationException extends Exception
{
    public function __construct($message = "Can not install module.")
    {
        parent::__construct($message, 500);
    }
}
