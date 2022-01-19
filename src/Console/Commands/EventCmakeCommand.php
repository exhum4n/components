<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

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

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
