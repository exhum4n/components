<?php

declare(strict_types=1);

namespace Exhum4n\Components\Models;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class QueueJob.
 *
 * @property int id
 * @property int attempts
 * @property string payload
 * @property Carbon reserved_at
 * @property Carbon available_at
 * @property Carbon created_at
 */
class QueueJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected string $table = 'queue_jobs.queue_jobs';

    protected array $fillable = [
        'queue',
        'attempts',
        'payload',
        'reserved_at',
        'available_at',
        'created_at'
    ];
}
