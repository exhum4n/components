<?php

declare(strict_types=1);

namespace Exhum4n\Components\Repositories;

class CounterRepository extends RedisRepository
{
    protected string $prefix = 'count';

    protected int $expirationTime = 600;

    public function getCount(string $key): int
    {
        return (int) $this->get($key);
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = "{$prefix}_count";
    }
}
