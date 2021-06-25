<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Illuminate\Support\Str;

abstract class Installer extends AbstractCommand
{
    /**
     * Array of package seeders.
     *
     * @var array
     */
    protected $seeds = [];

    /**
     * Handle console command.
     */
    public function handle(): void
    {
        $this->call('migrate', [
            '--path' => migrations_path(static::class),
        ]);

        $seeds = $this->seeds;
        foreach ($seeds as $seed) {
            $this->call('db:seed', ['--class' => $seed]);
        }
    }

    /**
     * @return string
     */
    protected function getEnvPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }

    /**
     * @param string $envPath
     * @param string $variable
     *
     * @return bool
     */
    protected function isEnvVariableExists(string $envPath, string $variable): bool
    {
        return Str::contains(file_get_contents($envPath), $variable);
    }

    /**
     * @param string $envPath
     * @param string $variable
     * @param string|null $value
     */
    protected function addEnvVariable(string $envPath, string $variable, ?string $value = null): void
    {
        file_put_contents($envPath, PHP_EOL . "$variable=$value", FILE_APPEND);
    }
}
