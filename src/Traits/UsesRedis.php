<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Exhum4n\Components\Repositories\RedisRepository;

trait UsesRedis
{
    protected RedisRepository $cache;

    abstract protected function getRedisRepository(): string;
}