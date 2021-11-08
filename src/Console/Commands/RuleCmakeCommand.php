<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\RuleMakeCommand;

class RuleCmakeCommand extends RuleMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:rule';

    protected string $relatedNamespace = '\Rules';
}
