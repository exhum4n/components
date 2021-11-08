<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ExceptionMakeCommand;

class ExceptionCmakeCommand extends ExceptionMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:exception';

    protected string $relatedNamespace = '\Exceptions';
}
