<?php

declare(strict_types=1);

namespace Exhum4n\Components\Services;

use Exhum4n\Components\Repositories\CounterRepository;
use Exhum4n\Components\Repositories\RedisRepository;
use Exhum4n\Components\Traits\UsesRedis;

class CountService extends Service
{
    use UsesRedis;

    /**
     * @var CounterRepository
     */
    protected RedisRepository $cache;

    public function __construct(string $prefix)
    {
        parent::__construct();

        $this->cache->setPrefix($prefix);
    }

    public function count(string $identifier): int
    {
        $attempts = $this->cache->getCount($identifier);

        $attempts++;

        $this->cache->set($identifier, $attempts);

        return $attempts;
    }

    public function clearCounts(string $identifier): void
    {
        $this->cache->delete($identifier);
    }

    protected function getRedisRepository(): string
    {
        return CounterRepository::class;
    }
}
