<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class InstallationException extends Exception
{
    #[Pure]
    public function __construct($message = "Can not install module.")
    {
        parent::__construct($message, 500);
    }
}
