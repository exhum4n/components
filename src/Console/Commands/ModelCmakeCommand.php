<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ModelCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:model';

    /**
     * @var string
     */
    protected $description = 'Create a new Eloquent model class';

    protected function getClassType(): string
    {
        return 'Model';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Models';
    }

    protected function getStub(): string
    {
        return $this->option('pivot')
            ? $this->resolveStubPath('/stubs/model/model.pivot.stub')
            : $this->resolveStubPath('/stubs/model/model.stub');
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
                'pivot',
                'p',
                InputOption::VALUE_NONE,
                'Indicates if the generated model should be a custom intermediate table model'
            ]
        ];
    }
}
