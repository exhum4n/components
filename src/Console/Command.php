<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    abstract public function handle(): void;

    abstract protected function getSignature(): string;

    public function __construct()
    {
        $this->signature = $this->getSignature();

        return parent::__construct();
    }
}
