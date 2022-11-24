<?php

declare(strict_types=1);

namespace Exhum4n\Components\Database\Migrations;

use Closure;
use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class Migration extends BaseMigration
{
    public string $tablePrefix;

    abstract protected function getSchema(): string;

    abstract protected function getTable(): string;

    abstract protected function getBlueprint(): Closure;

    public function table(): string
    {
        $schema = $this->getSchema();

        $table = $this->getTable();
        if (isset($this->tablePrefix)) {
            $table .= $this->tablePrefix;
        }

        return "$schema.$table";
    }

    public function up(): void
    {
        DB::connection()->statement("CREATE SCHEMA IF NOT EXISTS {$this->getSchema()}");

        Schema::create($this->table(), $this->getBlueprint());
    }

    public function createForeignKeys(): void
    {
        try {
            Schema::table($this->table(), $this->getForeignKeys());
        } catch (QueryException) {
            return;
        }
    }

    protected function getForeignKeys(): Closure
    {
        return static function () {};
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table());
    }
}
