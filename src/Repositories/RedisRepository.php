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

    protected Connection $redis;

    protected int $expirationTime = 86400;

    public function __construct(string $prefix = '')
    {
        $this->redis = Redis::connection();

        $this->prefix = $prefix;
    }

    public function set(string $key, $value): void
    {
        $this->redis->set("$this->prefix$key", $value, 'EX', $this->expirationTime);
    }

    public function get(string $key): ?string
    {
        return $this->redis->get("$this->prefix$key");
    }

    public function ttl(string $key): int
    {
        return $this->redis->ttl("$this->prefix$key");
    }

    protected function getPrefix(): string
    {
        return "$this->prefix:";
    }

    public function delete(string $key): int
    {
        return $this->redis->del("$this->prefix$key");
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
