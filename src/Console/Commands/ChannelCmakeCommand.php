<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\ChannelMakeCommand;

class ChannelCmakeCommand extends ChannelMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:channel';

    protected string $relatedNamespace = '\Broadcasting';


    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/channel.stub');
    }
}
