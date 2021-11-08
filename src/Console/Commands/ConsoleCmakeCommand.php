<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ConsoleMakeCommand;

class ConsoleCmakeCommand extends ConsoleMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:command';

    protected string $relatedNamespace = '\Console\Commands';
}
