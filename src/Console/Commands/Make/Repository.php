<?php

namespace Exhum4n\Components\Console\Commands\Make;

class Repository extends ClassGenerator
{
    protected string $class_prefix = 'Repository';

    protected function getActionName(): string
    {
        return 'repository {name : The name of repository model}';
    }

    protected function getStubName(): string
    {
        return 'repository.stub';
    }

    protected function getTargetFolder(): string
    {
        return 'Repositories';
    }

    protected function getStubVariables(): array
    {
        return [
            'namespace' => $this->getNamespace(),
            'classname' => $this->getClassName(),
        ];
    }
}
