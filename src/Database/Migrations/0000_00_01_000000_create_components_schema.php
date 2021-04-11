<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateComponentsSchema extends Migration
{
    public function up(): void
    {
        DB::connection()->statement("CREATE SCHEMA IF NOT EXISTS components");
    }

    /**
     * Drop component table.
     */
    public function down(): void
    {
        DB::connection()->statement("DROP SCHEMA IF EXISTS components");
    }
}
