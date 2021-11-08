<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ObserverMakeCommand;

class ObserverCmakeCommand extends ObserverMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:observer';

    protected string $relatedNamespace = '\Observers';

    protected array $brokenOptions = [
        'model',
    ];
}
