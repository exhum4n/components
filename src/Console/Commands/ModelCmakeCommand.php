<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;

class ModelCmakeCommand extends ModelMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:model';

    protected string $relatedNamespace = '\Models';

    protected array $brokenOptions = [
        'all',
        'controller',
        'factory',
        'migration',
        'policy',
        'seed',
        'resource',
        'api'
    ];
}
