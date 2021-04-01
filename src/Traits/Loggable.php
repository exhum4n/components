<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait Loggable
{
    /**
     * @var Logger
     */
    public $logger;

    /**
     * @var string
     */
    protected $channelName = 'app';

    /**
     * @param string|null $channelName
     */
    protected function initLogger(?string $channelName = null): void
    {
        $channelName = $channelName ?: $this->channelName;

        $this->logger = new Logger($channelName);

        $logPath = "logs/{$channelName}.log";

        $streamHandler = new StreamHandler(storage_path($logPath));

        $this->logger->pushHandler($streamHandler);
    }
}
