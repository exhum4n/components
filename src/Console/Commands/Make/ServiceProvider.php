<?php

namespace Exhum4n\Components\Console\Commands\Make;

use Illuminate\Support\Pluralizer;

class ServiceProvider extends ClassGenerator
{
    protected string $class_prefix = 'ServiceProvider';

    protected array $options = [
        'force' => 'Make command force',
    ];

    protected function getActionName(): string
    {
        return 'provider {name : The name of a service provider}';
    }

    protected function getStubName(): string
    {
        return 'service-provider.stub';
    }

    protected function getTargetFolder(): string
    {
        return 'Providers';
    }

    protected function getClassName(?bool $withPrefix = true): string
    {
        $classname = ucwords(Pluralizer::plural($this->component));

        return ucwords($classname . $this->class_prefix);
    }

    protected function getStubVariables(): array
    {
        return [
            'component' => Pluralizer::plural(strtolower($this->component)),
            'namespace' => $this->getNamespace(),
            'classname' => $this->getClassName(),
        ];
    }
}
