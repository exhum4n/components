<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait Loggable
{
    public Logger $logger;

    protected function initLogger(): void
    {
        $logName = $this->getLogName();

        $this->logger = new Logger($logName);

        $logPath = "logs/$logName.log";

        $streamHandler = new StreamHandler(storage_path($logPath));

        $this->logger->pushHandler($streamHandler);
    }

    abstract protected function getLogName(): string;
}
