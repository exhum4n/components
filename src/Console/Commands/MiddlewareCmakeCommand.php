<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class MiddlewareCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:middleware';

    /**
     * @var string
     */
    protected $description = 'Create a new middleware class';

    protected function getClassType(): string
    {
        return 'Middleware';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Http\Middleware';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/middleware/middleware.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
