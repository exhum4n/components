<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Exception;
use Throwable;

class LocaleNotSupported extends Exception
{
    public function __construct($locale, $code = 0, Throwable $previous = null)
    {
        parent::__construct("{$locale} locale is not supported.", $code, $previous);
    }
}
