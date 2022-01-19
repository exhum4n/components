<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:controller';
    protected $description = 'Create a new controller class';

    protected function getClassType(): string
    {
        return 'Controller';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Http\Controllers';
    }

    protected function getStub(): string
    {
        if ($this->option('model')) {
            $stub = $this->option('api')
                ? '/Stubs/controller/controller.model.api.stub'
                : '/Stubs/controller/controller.model.stub';
        } else {
            $stub = $this->option('api')
                ? '/Stubs/controller/controller.api.stub'
                : '/Stubs/controller/controller.stub';
        }

        return $this->resolveStubPath($stub);
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
                'api',
                null,
                InputOption::VALUE_NONE,
                'Exclude the create and edit methods from the controller.'
            ],
            [
                'model',
                'm',
                InputOption::VALUE_REQUIRED,
                'Generate a resource controller for the given model.'
            ]
        ];
    }
}
