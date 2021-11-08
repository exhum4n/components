<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ListenerMakeCommand;

class ListenerCmakeCommand extends ListenerMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:listener';

    protected string $relatedNamespace = '\Listeners';
}
