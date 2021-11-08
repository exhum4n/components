<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\TestMakeCommand;

class TestCmakeCommand extends TestMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:test';

    protected string $relatedNamespace = '\Tests\Feature';
}
