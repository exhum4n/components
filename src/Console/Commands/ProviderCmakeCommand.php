<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;

class ProviderCmakeCommand extends ProviderMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:provider';

    protected string $relatedNamespace = '\Providers';
}
