<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Exhum4n\Components\Services\CountService;

trait HasCounter
{
    public function count(string $identifier): int
    {
        return app(CountService::class, ['prefix' => $this->getCounterPrefix()])
            ->count($identifier);
    }

    public function resetCounter(string $identifier): void
    {
        app(CountService::class, ['prefix' => $this->getCounterPrefix()])
            ->clearCounts($identifier);
    }

    abstract protected function getCounterPrefix(): string;
}
