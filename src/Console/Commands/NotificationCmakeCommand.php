<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use JetBrains\PhpStorm\Pure;

class NotificationCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:notification';
    protected $description = 'Create a new notification class';

    protected function getClassType(): string
    {
        return 'Notification';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Notifications';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/notification/notification.stub');
    }

    #[Pure]
    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
