<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class TestCmakeCommand extends GeneratorCommand
{
    protected $name = 'cmake:testing';
    protected $description = 'Create a new test class';

    protected const TYPE = 'Test';
    protected const STUB_PATH = '/stubs/test/test.stub';

    function relativeNamespace(): string
    {
        if ($this->option('unit')) {
            return 'Test\Unit';
        }

        return 'Test\Feature';
    }

    protected function getStub(): string
    {
        $suffix = $this->option('unit') ? '.unit.stub' : '.stub';

        return $this->option('pest')
            ? $this->resolveStubPath('/stubs/pest' . $suffix)
            : $this->resolveStubPath('/stubs/test' . $suffix);
    }

    public function replaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getClassNamespace(),
            '{{ event }}' => $this->namespaceBuilder->createNamespace('Event', 'TestEvent'),
        ];
    }

    protected function getOptions(): array
    {
        return array_merge(
            [
                ['unit', 'u', InputOption::VALUE_NONE, 'Create a unit test.'],
                ['pest', 'p', InputOption::VALUE_NONE, 'Create a Pest test.'],
            ],
            parent::getOptions()
        );
    }
}
