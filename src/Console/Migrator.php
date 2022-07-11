<?php

namespace Exhum4n\Components\Console;

use Exception;
use Exhum4n\Components\Database\Migrations\PostgresMigration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class Migrator extends Command
{
    protected const MIGRATION_TABLE = 'migrations';
    protected const MIGRATION_COLUMN = 'migration';

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
    final function getMigrationClass(string $filePath): PostgresMigration
    {
        require_once $filePath;

        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new Exception('Empty migration file.');
        }

        preg_match('/class\s+(\w+).*?{/s', $fileContent, $matches);

        return new $matches[1]();
    }

    /**
     * @throws Exception
     */
    final function getMigrations(): array
    {
        $componentNames = $this->getDir(base_path('components'));

        $paths = [];

        foreach ($componentNames as $name) {
            $paths[] = "components/$name/Database/Migrations";
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
            $dirs = scandir($path);
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
