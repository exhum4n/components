<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\JobMakeCommand;

class JobCmakeCommand extends JobMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:job';

    protected string $relatedNamespace = '\Jobs';
}
