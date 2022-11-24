<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use Exhum4n\Components\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQueueJobsFailedTable extends Migration
{
    protected function getSchema(): string
    {
        return 'queue_jobs';
    }

    protected function getTable(): string
    {
        return 'failed';
    }

    protected function getBlueprint(): Closure
    {
        return static function (Blueprint $table): void {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        };
    }
}
