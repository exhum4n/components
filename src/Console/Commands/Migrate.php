<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\Migrator;
use Illuminate\Database\Console\Migrations\MigrateCommand as IlluminateMigrateCommand;

class Migrate extends Migrator
{
    protected $description = 'Run the components migrations';

    public function handle(): void
    {
        $this->runMigrations($this->hasOption('force'));
        $this->runForeignKeys();
    }

    protected function runMigrations(bool $force = false): void
    {
        foreach ($this->getMigrations() as $path) {
            $this->callMigrateCommand($path, $force);
        }
    }

    protected function callMigrateCommand(string $path, bool $force = false): void
    {
        $this->call(IlluminateMigrateCommand::class, [
            '--path' => $path,
            '--force' => $force
        ]);
    }

    protected function runForeignKeys(): void
    {
        $migrationsPaths = $this->getMigrations();

        foreach ($migrationsPaths as $migrationPath) {
            if ($this->checkIfMigrationAlreadyProcessed($migrationPath)) {
                continue;
            }

            $this->createForeignKeys($migrationPath);
        }
    }

    protected function createForeignKeys(string $migrationsDir): void
    {
        $migrations = $this->getDir($migrationsDir);
        if (empty($migrations)) {
            return;
        }

        foreach ($migrations as $migration) {
            $class = $this->getMigrationClass("$migrationsDir/$migration");

            $class->createForeignKeys();
        }
    }

    protected function getSignature(): string
    {
        return 'components:migrate {--force : Force the operation to run when in production}';
    }
}
