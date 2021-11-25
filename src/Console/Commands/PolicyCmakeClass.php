<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class PolicyCmakeClass extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:policy';

    /**
     * @var string
     */
    protected $description = 'Create a new policy class';

    protected function getClassType(): string
    {
        return 'Policy';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Policies';
    }

    protected function getStub(): string
    {
        return $this->option('model')
            ? $this->resolveStubPath('/stubs/policy/policy.stub')
            : $this->resolveStubPath('/stubs/policy/policy.plain.stub');
    }

    protected function getReplaces(): array
    {
        $model = $this->option('model');
        if ($model) {
            $namespacedModel = $this->namespaceBuilder->createNamespace("Models\\$model");
        }

        $namespacedUserProviderModel = $this->namespaceBuilder->getNamespacedUserProviderModel($this->option('guard'));
        $userModel = $this->namespaceBuilder->getClassFromNamespace($namespacedUserProviderModel);

        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ model }}' => $model,
            '{{ modelVariable }}' => lcfirst($model),
            '{{ namespacedModel }}' => $namespacedModel ?? null,
            '{{ namespacedUserModel }}' => $namespacedUserProviderModel,
            '{{ user }}' => $userModel,
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
            [
                'guard',
                'g',
                InputOption::VALUE_OPTIONAL,
                'The guard that the policy relies on'
            ]
        ];
    }
}
