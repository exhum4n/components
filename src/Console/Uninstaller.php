<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Illuminate\Support\Facades\DB;

abstract class Uninstaller extends AbstractCommand
{
    protected $dropSchema = true;

    public function handle(): void
    {
        $this->call('migrate:reset', [
            '--path' => migrations_path(static::class),
        ]);

        if ($this->dropSchema === true) {
            DB::statement("DROP SCHEMA IF EXISTS {$this->schemaName()}");
        }
    }

    private function schemaName(): string
    {
        $exploded = explode(':', $this->signature);

        return $exploded[0];
    }
}
