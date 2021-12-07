<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ResourceCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:resource';

    /**
     * @var string
     */
    protected $description = 'Create a new resource class';

    protected function getClassType(): string
    {
        return 'Resource';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Http\Resources';
    }

    protected function getStub(): string
    {
        return $this->option('collection')
            ? $this->resolveStubPath('/stubs/resource/resource-collection.stub')
            : $this->resolveStubPath('/stubs/resource/resource.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'collection',
                'c',
                InputOption::VALUE_NONE,
                'Create a resource collection'
            ]
        ];
    }
}
