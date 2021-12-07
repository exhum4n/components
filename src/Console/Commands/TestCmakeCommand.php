<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class TestCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:test';

    /**
     * @var string
     */
    protected $description = 'Create a new test class';

    protected function getClassType(): string
    {
        return 'Test';
    }

    protected function getRelativeNamespace(): string
    {
        if ($this->option('unit')) {
            return 'Tests\Unit';
        }

        return 'Tests\Feature';
    }

    protected function getStub(): string
    {
        $suffix = $this->option('unit') ? '.unit.stub' : '.stub';

        return $this->option('pest')
            ? $this->resolveStubPath('/stubs/test/pest' . $suffix)
            : $this->resolveStubPath('/stubs/test/test' . $suffix);
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
                'unit',
                'u',
                InputOption::VALUE_NONE,
                'Create a unit test.'
            ],
            [
                'pest',
                'p',
                InputOption::VALUE_NONE,
                'Create a Pest test.'
            ],
        ];
    }
}
