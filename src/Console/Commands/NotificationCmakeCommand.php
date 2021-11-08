<?php

namespace Exhum4n\Components\Console\Commands;

class NotificationCmakeCommand extends \Illuminate\Foundation\Console\NotificationMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:notification';

    protected string $relatedNamespace = '\Notifications';
}
