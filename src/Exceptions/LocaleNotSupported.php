<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class LocaleNotSupported extends Exception
{
    #[Pure]
    public function __construct($locale)
    {
        parent::__construct("$locale locale is not supported.", 400);
    }
}
