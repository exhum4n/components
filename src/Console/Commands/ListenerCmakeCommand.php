<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ListenerCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:listener';

    /**
     * @var string
     */
    protected $description = 'Create a new listener class';

    protected function getClassType(): string
    {
        return 'Listener';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Listeners';
    }

    protected function getStub(): string
    {
        if ($this->option('queued')) {
            return $this->option('event')
                ? $this->resolveStubPath('/stubs/listener/listener-queued.stub')
                : $this->resolveStubPath('/stubs/listener/listener-queued-duck.stub');
        }

        return $this->option('event')
            ? $this->resolveStubPath('/stubs/listener/listener.stub')
            : $this->resolveStubPath('/stubs/listener/listener-duck.stub');
    }

    protected function getReplaces(): array
    {
        $event = $this->option('event');

        if ($event) {
            $eventNamespace = $this->namespaceBuilder->createNamespace("Events\\$event");
        }

        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ event }}' => $event,
            '{{ eventNamespace }}' => $eventNamespace ?? null
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'event',
                'e',
                InputOption::VALUE_OPTIONAL,
                'The event class being listened for'
            ],
            [
                'queued',
                'Q',
                InputOption::VALUE_NONE,
                'Indicates the event listener should be queued'
            ],
        ];
    }
}
