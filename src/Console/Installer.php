<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

abstract class Installer extends AbstractCommand
{
    protected $seeds = [];

    public function handle(): void
    {
        $this->call('migrate', ['--path' => migrations_path(static::class)]);

        $seeds = $this->seeds;
        foreach ($seeds as $seed) {
            $this->call('db:seed', ['--class' => $seed]);
        }
    }
}
