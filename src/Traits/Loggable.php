<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

/**
 * @property string logName
 */
trait Loggable
{
    public LoggerInterface $log;

    abstract protected function getLogName(): string;

    protected function initLogger(): void
    {
        $this->log = Log::build([
            'driver' => 'single',
            'path' => storage_path("logs/{$this->getLogName()}.log"),
        ]);
    }
}
