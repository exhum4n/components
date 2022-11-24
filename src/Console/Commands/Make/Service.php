<?php

namespace Exhum4n\Components\Console\Commands\Make;

class Service extends ClassGenerator
{
    protected string $class_prefix = 'Service';

    protected function getActionName(): string
    {
        return 'service {name : The name of a service}';
    }

    protected function getStubName(): string
    {
        return 'service.stub';
    }

    protected function getTargetFolder(): string
    {
        return 'Services';
    }

    protected function getStubVariables(): array
    {
        return [
            'namespace' => $this->getNamespace(),
            'classname' => $this->getClassName(),
        ];
    }
}
