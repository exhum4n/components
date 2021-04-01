<?php

declare(strict_types=1);

namespace Exhum4n\Components\Database\Migrations;

use Closure;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class PostgresMigration extends Migration
{
    /**
     * Receive component schema name.
     *
     * @return string
     */
    abstract protected function getSchema(): string;

    /**
     * Receive table name.
     *
     * @return string
     */
    abstract protected function getTable(): string;

    /**
     * Get table structure.
     *
     * @return Closure
     */
    abstract protected function getBlueprint(): Closure;

    /**
     * Returns full table path.
     *
     * @return string
     */
    protected function table(): string
    {
        return "{$this->getSchema()}.{$this->getTable()}";
    }

    /**
     * Migrate component table.
     */
    public function up(): void
    {
        DB::connection()->statement("CREATE SCHEMA IF NOT EXISTS {$this->getSchema()}");

        Schema::create($this->table(), $this->getBlueprint());
    }

    /**
     * Drop component table.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table());
    }
}
