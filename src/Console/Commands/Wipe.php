<?php

namespace Exhum4n\Components\Console\Commands;

use Exception;
use Exhum4n\Components\Catalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Wipe extends Command
{
    protected $description = 'Wipe components data';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->getMigrations() as $path) {
            $this->truncateTables($path);
        }

        Schema::enableForeignKeyConstraints();
    }

    protected function truncateTables(string $path): void
    {
        foreach ($this->resolveFiles($path) as $file) {
            DB::table($this->resolveMigration($file, $path)->table())->truncate();
        }
    }

    protected function getMigrationClass(string $migrationName): string
    {
        return Str::studly(implode('_', array_slice(explode('_', $migrationName), 4)));
    }

    protected function resolveMigration(string $file, string $path)
    {
        $path = base_path("$path/$file");

        require_once($path);

        $migrationClass = $this->getMigrationClass($this->getMigrationName($path));

        return new $migrationClass;
    }

    protected function getMigrationName(string $path): string
    {
        return str_replace('.php', '', basename($path));
    }

    protected function resolveFiles(string $path): array
    {
        return array_diff(scandir(base_path($path)), ['.', '..']);
    }

    protected function getActionName(): string
    {
        return 'wipe';
    }

    protected function truncateTable(string $table): void
    {
        try {
            DB::table($table)->truncate();
        } catch (Exception) {
            return;
        }
    }

    private function getMigrations(): array
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
}
