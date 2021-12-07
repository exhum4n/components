<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class NotificationCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:notification';

    /**
     * @var string
     */
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
        return $this->resolveStubPath('/stubs/notification/notification.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
