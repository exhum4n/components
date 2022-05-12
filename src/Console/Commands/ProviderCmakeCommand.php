<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use JetBrains\PhpStorm\Pure;

class ProviderCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:provider';
    protected $description = 'Create a new service provider class';

    protected function getClassType(): string
    {
        return 'Service provider';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Providers';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/provider/provider.stub');
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
