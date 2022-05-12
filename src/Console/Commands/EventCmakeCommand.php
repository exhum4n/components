<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use JetBrains\PhpStorm\Pure;

class EventCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:event';
    protected $description = 'Create a new event class';

    protected function getClassType(): string
    {
        return 'Event';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Events';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/event/event.stub');
    }

    #[Pure]
    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
