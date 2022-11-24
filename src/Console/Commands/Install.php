<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Database\Seeds\Seeder;

class Install extends Command
{
    protected array $seeds = [];

    public function handle(): void
    {
        $this->call('migrate', [
            '--path' => $this->getMigrationsPath(),
        ]);

        foreach ($this->seeds as $seed) {
            if ($seed instanceof Seeder === false) {
                continue;
            }

            $this->call('db:seed', ['--class' => $seed]);
        }
    }

    protected function getActionName(): string
    {
        return 'install';
    }

    protected function getMigrationsPath(): string
    {
        $components = components_catalog()->toArray();

        return migrations_path($components[$this->getComponentName()]);
    }
}
