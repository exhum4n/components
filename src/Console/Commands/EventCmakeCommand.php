<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\EventMakeCommand;

class EventCmakeCommand extends EventMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:event';

    protected string $relatedNamespace = '\Events';
}
