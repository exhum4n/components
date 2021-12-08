<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Console\Command as IlluminateCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand as IlluminateMigrateCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class MigrateCommand extends IlluminateCommand
{
    protected const MIGRATION_TABLE = 'migrations';
    protected const MIGRATION_COLUMN = 'migration';

    protected $name = 'components:migrate';

    protected ConsoleOutput $consoleOutput;

    /**
     * @var array<string>
     */
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

        $this->createForeignKeys();

        return Command::SUCCESS;
    }

    protected function getCompletedMigrations(): array
    {
        if ($this->checkIfMigrationsTableExists()) {
            return DB::table(static::MIGRATION_TABLE)
                ->get(static::MIGRATION_COLUMN)
                ->pluck(static::MIGRATION_COLUMN)
                ->toArray();
        }

        return [];
    }

    protected function checkIfMigrationsTableExists(): bool
    {
        return Schema::hasTable(static::MIGRATION_TABLE);
    }

    protected function runMigrations(): void
    {
        foreach ($this->getComponentPaths() as $path) {
            $this->callMigrateCommand($path);
        }
    }

    protected function callMigrateCommand(string $path): void
    {
        $exitCode = Artisan::call(
            IlluminateMigrateCommand::class,
            ['--path' => $path],
            $this->consoleOutput
        );

        if ($exitCode !== Command::SUCCESS) {
            throw new RuntimeException('Can`t run artisan make:migrate command.');
        }
    }

    protected function createForeignKeys(): void
    {
        $migrationPaths = $this->getAllMigrationPaths();

        foreach ($migrationPaths as $migrationPath) {
            if ($this->checkIfMigrationAlreadyProcessed($migrationPath)) {
                continue;
            }

            $this->createForeignKey($migrationPath);
        }
    }

    protected function getAllMigrationPaths(): array
    {
        $migrationPaths = [];

        foreach ($this->getComponentPaths() as $componentPath) {
            $migrationPaths[] = $this->createComponentMigrationPaths($componentPath);
        }

        return array_merge(...$migrationPaths);
    }

    protected function createForeignKey(string $migrationPath): void
    {
        require_once $migrationPath;

        $className = $this->getClassName($migrationPath);

        (new $className())->addForeignKey();
    }

    protected function getComponentPaths(): array
    {
        $componentNames = $this->scanDirectory(base_path('components'));

        $paths = [];

        foreach ($componentNames as $name) {
            $paths[] = "components/$name/Database/Migrations";
        }

        return $paths;
    }

    protected function createComponentMigrationPaths(string $path): array
    {
        $migrationFiles = $this->scanDirectory(base_path($path));

        $paths = [];

        foreach ($migrationFiles as $name) {
            $paths[] = "$path/$name";
        }

        return $paths;
    }

    protected function checkIfMigrationAlreadyProcessed(string $file): bool
    {
        $file = preg_replace('/(?<dir>.+?)\/?(?<file>(?<name>\w+)\.(?<ext>\w+))?$/m', '$3', $file);

        return in_array($file, $this->completedMigrations, true);
    }

    protected function getClassName(string $filePath): string
    {
        $fileContent = $this->getFileContent($filePath);

        preg_match('/class\s+(\w+).*?{/s', $fileContent, $matches);

        return $matches[1];
    }

    protected function getFileContent(string $path): string
    {
        $fileContent = file_get_contents($path);
        if ($fileContent === false) {
            throw new RuntimeException('Empty migration file.');
        }

        return $fileContent;
    }

    protected function scanDirectory(string $path): array
    {
        $dirs = scandir($path);
        if (empty($dirs)) {
            throw new RuntimeException("$path is not a directory!");
        }

        $dirsWithoutDots = array_slice($dirs, 2);
        if ($dirsWithoutDots === false) {
            throw new RuntimeException('Can`t remove dots from directories!');
        }

        return $dirsWithoutDots;
    }
}
