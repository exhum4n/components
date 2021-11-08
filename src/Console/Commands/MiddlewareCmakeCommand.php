<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Routing\Console\MiddlewareMakeCommand;

class MiddlewareCmakeCommand extends MiddlewareMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:middleware';

    protected string $relatedNamespace = '\Http\Middleware';
}
