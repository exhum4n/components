<?php

declare(strict_types=1);

namespace Exhum4n\Components\Database\Migrations;

use Closure;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class PostgresMigration extends Migration
{
    abstract protected function getSchema(): string;

    abstract protected function getTable(): string;

    abstract protected function getBlueprint(): Closure;

    protected function table(): string
    {
        return "{$this->getSchema()}.{$this->getTable()}";
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
        } catch (QueryException $exception) {
            return;
        }
    }

    protected function getForeignKeys(): Closure
    {
        return function () {};
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table());
    }
}
