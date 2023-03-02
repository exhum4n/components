<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

trait UsesRedis
{
    abstract protected function getRedisRepository(): string;
}
