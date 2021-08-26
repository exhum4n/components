<?php

declare(strict_types=1);

namespace Exhum4n\Components\Repositories;

use Illuminate\Support\Facades\Redis;

class RedisRepository
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @var int
     */
    protected $expirationTime = 86400;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->redis->set("{$this->getPrefix()}{$key}", $value, 'EX', $this->expirationTime);
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->redis->get("{$this->getPrefix()}{$key}");
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function ttl(string $key): int
    {
        return $this->redis->ttl("{$this->getPrefix()}{$key}");
    }

    protected function getPrefix(): string
    {
        return "$this->prefix:";
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function delete(string $key)
    {
        return $this->redis->del("{$this->getPrefix()}{$key}");
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function isExists(string $key, string $value): bool
    {
        return $this->get($key) === $value;
    }

    /**
     * @param int $seconds
     */
    public function setExpirationTime(int $seconds): void
    {
        $this->expirationTime = $seconds;
    }
}
