<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ConsoleCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:command';
    protected $description = 'Create a new Artisan command';

    protected function getClassType(): string
    {
        return 'Command';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Console\Commands';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/console/console.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ command }}' => $this->option('command')
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'command',
                null,
                InputOption::VALUE_OPTIONAL,
                'The terminal command that should be assigned [default: "command:name"]'
            ]
        ];
    }
}
