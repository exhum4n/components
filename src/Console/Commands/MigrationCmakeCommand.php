<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class MigrationCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:migration';

    /**
     * @var string
     */
    protected $description = 'Create a new migration anonymous class';

    protected function getClassType(): string
    {
        return 'Migration';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Databases\Migrations';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/migration/migration.create.stub');
    }

    protected function executeCommand(): int
    {
        if ($this->option('schema') === null) {
            $this->error('--schema (-s) option required!');

            return Command::INVALID;
        }

        if ($this->option('table') === null) {
            $this->error('--table (-t) option required!');

            return Command::INVALID;
        }

        return parent::executeCommand();
    }

    protected function getReplaces(): array
    {
        return [
            '{{ schema }}' => $this->option('schema'),
            '{{ table }}' => $this->option('table')
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'schema',
                's',
                InputOption::VALUE_REQUIRED,
                'Define a schema.'
            ],
            [
                'table',
                't',
                InputOption::VALUE_REQUIRED,
                'Define a table.'
            ],
        ];
    }
}
