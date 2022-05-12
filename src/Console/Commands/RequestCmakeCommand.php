<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use JetBrains\PhpStorm\Pure;

class RequestCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:request';
    protected $description = 'Create a new form request class';

    protected function getClassType(): string
    {
        return 'Request';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Http\Requests';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/request/request.stub');
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
