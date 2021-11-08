<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;

class ControllerCmakeCommand extends ControllerMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:controller';

    protected string $relatedNamespace = '\Http\Controllers';

    protected array $brokenOptions = [
        'model',
        'parent',
    ];
}
