<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ComponentMakeCommand;

class ComponentCmakeCommand extends ComponentMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:component';

    protected string $relatedNamespace = '\View\Components';
}
