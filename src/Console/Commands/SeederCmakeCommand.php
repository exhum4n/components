<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use JetBrains\PhpStorm\Pure;

class SeederCmakeCommand extends CmakeCommand
{
    protected $name = 'cmake:seeder';
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
        return $this->resolveStubPath('/Stubs/seeder/seeder.stub');
    }

    #[Pure]
    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
