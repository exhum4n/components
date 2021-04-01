<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    abstract public function handle(): void;

    abstract protected function getSignature(): string;

    protected $signature;

    public function __construct()
    {
        $this->signature = $this->getSignature();

        return parent::__construct();
    }
}
