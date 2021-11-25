<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class JobCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:job';

    /**
     * @var string
     */
    protected $description = 'Create a new job class';

    protected function getClassType(): string
    {
        return 'Job';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Jobs';
    }

    protected function getStub(): string
    {
        return $this->option('sync')
            ? $this->resolveStubPath('/stubs/job/job.stub')
            : $this->resolveStubPath('/stubs/job/job.queued.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'sync',
                null,
                InputOption::VALUE_NONE,
                'Indicates that job should be synchronous'
            ]
        ];
    }
}
