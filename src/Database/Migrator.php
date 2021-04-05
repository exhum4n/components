<?php

declare(strict_types=1);

namespace Exhum4n\Components\Database;

use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Support\Arr;
use Illuminate\Database\Migrations\Migrator as BaseMigrator;

class Migrator extends BaseMigrator
{
    protected function rollbackMigrations(array $migrations, $paths, array $options): array
    {
        $rolledBack = [];

        $this->requireFiles($files = $this->getMigrationFiles($paths));

        $this->fireMigrationEvent(new MigrationsStarted());

        foreach ($migrations as $migration) {
            $migration = (object) $migration;

            if (! $file = Arr::get($files, $migration->migration)) {
                continue;
            }

            $rolledBack[] = $file;

            $this->runDown(
                $file,
                $migration,
                $options['pretend'] ?? false
            );
        }

        $this->fireMigrationEvent(new MigrationsEnded());

        return $rolledBack;
    }
}
