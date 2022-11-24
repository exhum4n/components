<?php

declare(strict_types=1);

namespace Exhum4n\Components\Services;

use Exhum4n\Components\Repositories\CounterRepository;
use Exhum4n\Components\Traits\UsesRedis;

/**
 * @property CounterRepository $cache
 */
class CountService extends Service
{
    use UsesRedis;

    public function __construct(string $prefix)
    {
        parent::__construct();

        $this->cache->setPrefix($prefix);
    }

    public function increment(string $identifier): int
    {
        $attempts = $this->cache->getCount($identifier);

        $attempts++;

        $this->cache->set($identifier, $attempts);

        return $attempts;
    }

    public function reset(string $identifier): void
    {
        $this->cache->delete($identifier);
    }

    protected function getRedisRepository(): string
    {
        return CounterRepository::class;
    }
}
