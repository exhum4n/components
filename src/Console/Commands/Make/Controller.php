<?php

namespace Exhum4n\Components\Console\Commands\Make;

class Controller extends ClassGenerator
{
    protected string $class_prefix = 'Controller';

    protected function getActionName(): string
    {
        return 'controller {name : The name of the controller}';
    }

    protected function getStubName(): string
    {
        return 'controller.stub';
    }

    protected function getTargetFolder(): string
    {
        return 'Http' . DIRECTORY_SEPARATOR . 'Controllers';
    }

    protected function getStubVariables(): array
    {
        return [
            'namespace' => $this->getNamespace(),
            'classname' => $this->getClassName()
        ];
    }
}
