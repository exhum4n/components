<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\CastMakeCommand;

class CastCmakeCommand extends CastMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:cast';

    protected string $relatedNamespace = '\Casts';
}
