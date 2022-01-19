<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ObserverCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:observer';
    protected $description = 'Create a new observer class';

    protected function getClassType(): string
    {
        return 'Observer';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Observers';
    }

    protected function getStub(): string
    {
        return $this->option('model')
            ? $this->resolveStubPath('/Stubs/observer/observer.stub')
            : $this->resolveStubPath('/Stubs/observer/observer.plain.stub');
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
            '{{ modelVariable }}' => lcfirst($model),
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
                'The model that the observer applies to.'
            ]
        ];
    }
}
