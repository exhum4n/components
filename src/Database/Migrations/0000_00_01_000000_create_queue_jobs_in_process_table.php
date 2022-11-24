<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use Exhum4n\Components\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQueueJobsInProcessTable extends Migration
{
    protected function getSchema(): string
    {
        return 'queue_jobs';
    }

    protected function getTable(): string
    {
        return 'in_process';
    }

    protected function getBlueprint(): Closure
    {
        return static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('queue', 20);
            $table->unsignedInteger('attempts');
            $table->text('payload');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        };
    }
}
