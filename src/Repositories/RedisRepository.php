<?php

/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Repositories;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

class RedisRepository
{
    protected string $prefix;

    protected Connection $cache;

    protected int $expirationTime = 86400;

    public function __construct(string $prefix = '')
    {
        $this->cache = Redis::connection();

        $this->prefix = $prefix;
    }

    public function set(string $key, $value): void
    {
        $this->cache->set("$this->prefix$key", $value, 'EX', $this->expirationTime);
    }

    public function get(string $key): ?string
    {
        return $this->cache->get("$this->prefix$key");
    }

    public function ttl(string $key): int
    {
        return $this->cache->ttl("$this->prefix$key");
    }

    protected function getPrefix(): string
    {
        return "$this->prefix:";
    }

    public function delete(string $key): int
    {
        return $this->cache->del("$this->prefix$key");
    }

    public function isExists(string $key, string $value): bool
    {
        return $this->get($key) === $value;
    }

    public function setExpirationTime(int $seconds): void
    {
        $this->expirationTime = $seconds;
    }
}
