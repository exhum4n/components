<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class CastCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:cast';
    protected $description = 'Create a new cast class';

    protected function getClassType(): string
    {
        return 'Cast';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Casts';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/cast/cast.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
