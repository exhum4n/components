<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class RequestCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:request';

    /**
     * @var string
     */
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
        return $this->resolveStubPath('/stubs/request/request.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
