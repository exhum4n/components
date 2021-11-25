<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class SeederCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:seeder';

    /**
     * @var string
     */
    protected $description = 'Create a new seeder class';

    protected function getClassType(): string
    {
        return 'Seeder';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Database\Seeders';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/seeder/seeder.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
