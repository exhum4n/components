<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class ProviderCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:provider';

    /**
     * @var string
     */
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
        return $this->resolveStubPath('/stubs/provider/provider.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
