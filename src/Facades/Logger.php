<?php

namespace Exhum4n\Components\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static log(array $data): void
 * @method static writeLog(string $event, string $logName, string $type): void
 */
class Logger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Logger';
    }
}
