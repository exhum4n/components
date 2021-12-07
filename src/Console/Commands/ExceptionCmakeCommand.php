<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ExceptionCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:exception';

    /**
     * @var string
     */
    protected $description = 'Create a new exception class';

    protected function getClassType(): string
    {
        return 'Exception';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Exceptions';
    }

    protected function getStub(): string
    {
        if ($this->option('render')) {
            $stub = $this->option('report')
                ? '/stubs/exception/exception-render-report.stub'
                : '/stubs/exception/exception-render.stub';
        } else {
            $stub = $this->option('report')
                ? '/stubs/exception/exception-report.stub'
                : '/stubs/exception/exception.stub';
        }

        return $this->resolveStubPath($stub);
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
                'render',
                null,
                InputOption::VALUE_NONE,
                'Create the exception with an empty render method'
            ],
            [
                'report',
                'm',
                InputOption::VALUE_NONE,
                'Create the exception with an empty report method'
            ]
        ];
    }
}
