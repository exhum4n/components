<?php

/** @noinspection PhpUndefinedConstantInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Illuminate\Console\Command as BaseCommand;

abstract class AbstractCommand extends BaseCommand
{
    protected array $options = [];

    abstract public function handle(): void;
    abstract protected function getActionName(): string;
    abstract protected function getComponentName(): string;

    public function __construct()
    {
        $this->signature = $this->getComponentName() . ':' . $this->getActionName();

        parent::__construct();
    }

    protected function showScriptDuration(): void
    {
        $duration = microtime(true) - LARAVEL_START;

        $this->info(sprintf('Script duration is %.4F sec.', $duration));
    }

    protected function showMemoryUsage(): void
    {
        $this->info("Peak memory usage is " . getMemoryPeakUsage());
    }
}
