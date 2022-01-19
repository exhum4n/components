<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class RepositoryCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:repository';
    protected $description = 'Create a new repository class';

    protected function getClassType(): string
    {
        return 'Repository';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Repositories';
    }

    protected function getStub(): string
    {
        return $this->option('model')
            ? $this->resolveStubPath('/Stubs/repository/repository.stub')
            : $this->resolveStubPath('/Stubs/repository/repository.plain.stub');
    }

    protected function getReplaces(): array
    {
        $model = $this->option('model');
        if ($model) {
            $namespacedModel = $this->namespaceBuilder->createNamespace("Models\\$model");
        }

        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ model }}' => $model,
            '{{ namespacedModel }}' => $namespacedModel ?? null,
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'model',
                'm',
                InputOption::VALUE_REQUIRED,
                'The model that the policy applies to'
            ],
        ];
    }
}
