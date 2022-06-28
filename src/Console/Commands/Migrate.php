<?php

namespace Exhum4n\Components\Console\Commands;

use Exception;
use Exhum4n\Components\Database\Migrations\PostgresMigration;
use Illuminate\Console\Command as IlluminateCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand as IlluminateMigrateCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class Migrate extends IlluminateCommand
{
    protected const MIGRATION_TABLE = 'migrations';
    protected const MIGRATION_COLUMN = 'migration';

    protected $name = 'components:migrate';
    protected ConsoleOutput $consoleOutput;
    protected array $completedMigrations;

    public function __construct()
    {
        parent::__construct();

        $this->consoleOutput = new ConsoleOutput();
    }

    public function handle(): int
    {
        $this->completedMigrations = $this->getCompletedMigrations();

        $this->runMigrations();
        $this->runForeignKeys();

        return Command::SUCCESS;
    }

    protected function getCompletedMigrations(): array
    {
        if ($this->checkIfMigrationsTableExists() === false) {
            return [];
        }

        return DB::table(static::MIGRATION_TABLE)
            ->get(static::MIGRATION_COLUMN)
            ->pluck(static::MIGRATION_COLUMN)
            ->toArray();
    }

    protected function checkIfMigrationsTableExists(): bool
    {
        return Schema::hasTable(static::MIGRATION_TABLE);
    }

    protected function runMigrations(): void
    {
        $paths = $this->getMigrationsPaths();

        foreach ($paths as $path) {
            $this->callMigrateCommand($path);
        }
    }

    protected function callMigrateCommand(string $path): void
    {
        $exitCode = $this->call(IlluminateMigrateCommand::class, ['--path' => $path]);

        if ($exitCode !== Command::SUCCESS) {
            throw new RuntimeException('Can`t run artisan make:migrate command.');
        }
    }

    protected function runForeignKeys(): void
    {
        $migrationsPaths = $this->getMigrationsPaths();

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
            $migrationFile = "$migrationsDir/$migration";

            $migrationClass = $this->getMigrationClass($migrationFile);

            $migrationClass->createForeignKeys();
        }
    }

    protected function getMigrationClass(string $filePath): PostgresMigration
    {
        require_once $filePath;

        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new RuntimeException('Empty migration file.');
        }

        preg_match('/class\s+(\w+).*?{/s', $fileContent, $matches);

        return new $matches[1]();
    }

    protected function getMigrationsPaths(): array
    {
        $componentNames = $this->getDir(base_path('components'));

        $paths = [];

        foreach ($componentNames as $name) {
            $paths[] = "components/$name/Database/Migrations";
        }

        return $paths;
    }

    protected function checkIfMigrationAlreadyProcessed(string $file): bool
    {
        $file = preg_replace('/(?<dir>.+?)\/?(?<file>(?<name>\w+)\.(?<ext>\w+))?$/m', '$3', $file);

        return in_array($file, $this->completedMigrations, true);
    }

    protected function getDir(string $path): array
    {
        try {
            $dirs = scandir($path);
        } catch (Exception) {
            return [];
        }

        $dirsWithoutDots = array_slice($dirs, 2);
        if ($dirsWithoutDots === false) {
            throw new RuntimeException('Can`t remove dots from directories!');
        }

        return $dirsWithoutDots;
    }
}
