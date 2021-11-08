<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;

class RequestCmakeCommand extends RequestMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:request';

    protected string $relatedNamespace = '\Http\Requests';
}
