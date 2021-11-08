<?php

namespace Exhum4n\Components\Console\Commands;

class PolicyCmakeCommand extends \Illuminate\Foundation\Console\PolicyMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:policy';

    protected string $relatedNamespace = '\Policies';

    protected array $brokenOptions = [
        'model',
        'guard',
    ];
}
