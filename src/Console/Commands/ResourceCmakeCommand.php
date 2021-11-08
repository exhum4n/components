<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;

class ResourceCmakeCommand extends ResourceMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:resource';

    protected string $relatedNamespace = '\Rules';
}
