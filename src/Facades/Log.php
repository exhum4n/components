<?php

namespace Exhum4n\Components\Facades;

use Illuminate\Support\Facades\Facade;
use Exhum4n\Components\Tools\Logger;

/**
 * @method static Logger collect(array $data)
 * @method static void save(string $event, string $logName, string $type)
 */
class Log extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Log';
    }
}
