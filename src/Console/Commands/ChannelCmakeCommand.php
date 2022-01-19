<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class ChannelCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:channel';
    protected $description = 'Create a new channel class';

    protected function getClassType(): string
    {
        return 'Channel';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Broadcasting';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/channel/channel.stub');
    }

    protected function getReplaces(): array
    {
        $modelNamespace = $this->namespaceBuilder->getNamespacedUserProviderModel();
        $model = $this->namespaceBuilder->getClassFromNamespace($modelNamespace);

        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ model }}' => $model,
            '{{ modelNamespace }}' => $modelNamespace
        ];
    }
}
