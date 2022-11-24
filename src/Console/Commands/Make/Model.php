<?php

namespace Exhum4n\Components\Console\Commands\Make;

class Model extends ClassGenerator
{
    protected function getActionName(): string
    {
        return 'model {name : The name of the model}';
    }

    protected function getStubName(): string
    {
        return 'model.stub';
    }

    protected function getTargetFolder(): string
    {
        return 'Models';
    }

    protected function getStubVariables(): array
    {
        return [
            'namespace' => $this->getNamespace(),
            'classname' => $this->getClassName(),
            'component' => $this->getPluralName($this->component, false),
            'table' => $this->getPluralName($this->name, false)
        ];
    }
}
