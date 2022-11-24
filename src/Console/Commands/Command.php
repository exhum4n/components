<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Catalog;
use Exhum4n\Components\Console\AbstractCommand;

abstract class Command extends AbstractCommand
{
    protected function getComponentName(): string
    {
        return Catalog::MAIN_COMPONENT_NAME;
    }
}
