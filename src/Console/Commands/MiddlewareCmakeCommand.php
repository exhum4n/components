<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use JetBrains\PhpStorm\Pure;

class MiddlewareCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:middleware';
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
        return $this->resolveStubPath('/Stubs/middleware/middleware.stub');
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
