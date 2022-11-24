<?php

namespace Exhum4n\Components\Console\Commands;

use Exception;
use Exhum4n\Components\Catalog;
use Exhum4n\Components\Database\Migrations\Migration;
use Illuminate\Database\Console\Migrations\MigrateCommand as IlluminateMigrateCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Migrate extends Command
{
    protected const MIGRATION_TABLE = 'migrations';
    protected const MIGRATION_COLUMN = 'migration';

    protected $description = 'Run the components migrations';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $this->runMigrations();
        $this->runForeignKeys();
    }

    protected function getActionName(): string
    {
        return 'migrate';
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    final function runForeignKeys(): void
    {
        $migrationsPaths = $this->getMigrations();

        foreach ($migrationsPaths as $migrationPath) {
            if ($this->checkIfMigrationAlreadyProcessed($migrationPath)) {
                continue;
            }

            $this->createForeignKeys($migrationPath);
        }
    }

    /**
     * @throws Exception
     */
    final function createForeignKeys(string $migrationsDir): void
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

    final function getCompletedMigrations(): array
    {
        if ($this->checkIfMigrationsTableExists() === false) {
            return [];
        }

        return DB::table(static::MIGRATION_TABLE)
            ->get(static::MIGRATION_COLUMN)
            ->pluck(static::MIGRATION_COLUMN)
            ->toArray();
    }

    final function checkIfMigrationsTableExists(): bool
    {
        return Schema::hasTable(static::MIGRATION_TABLE);
    }

    /**
     * @throws Exception
     */
    final function getMigrationClass(string $filePath): Migration
    {
        require_once base_path($filePath);

        $fileContent = file_get_contents(base_path($filePath));
        if ($fileContent === false) {
            throw new Exception('Empty migration file.');
        }

        preg_match('/class\s+(\w+).*?{/s', $fileContent, $matches);

        return new $matches[1]();
    }

    final function getMigrations(): array
    {
        $paths = [];

        $components = components_catalog()->toArray();

        foreach ($components as $name => $provider) {
            if ($name === Catalog::MAIN_COMPONENT_NAME) {
                continue;
            }

            $packageRoot = $provider->getPackageRoot();

            $paths[] = "$packageRoot/Database/Migrations";
        }

        return $paths;
    }

    final function checkIfMigrationAlreadyProcessed(string $file): bool
    {
        $file = preg_replace('/(?<dir>.+?)\/?(?<file>(?<name>\w+)\.(?<ext>\w+))?$/m', '$3', $file);

        return in_array($file, $this->getCompletedMigrations(), true);
    }

    /**
     * @throws Exception
     */
    final function getDir(string $path): array
    {
        try {
            $dirs = scandir(base_path($path));
        } catch (Exception) {
            return [];
        }

        $dirsWithoutDots = array_slice($dirs, 2);
        if ($dirsWithoutDots === false) {
            throw new Exception('Can`t remove dots from directories!');
        }

        return $dirsWithoutDots;
    }
}
